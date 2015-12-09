function showArticle(str) {
    if (str.length == 0) {
        document.getElementById('hintArticle').innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                datas = xmlhttp.responseText;
                console.log(datas);


                arr = JSON.parse(datas);

                document.getElementById('hintArticle').innerHTML = "<canvas id='updating-chart-Article' width='1080' height='500'></canvas><p>N. of Viewer " + arr['nViewers'] +"</p>";


                var canvas = document.getElementById('updating-chart-Article'),
                    ctx = canvas.getContext('2d'),
                    startingData = {
                        labels: ["StartReading %", 'StartReadingAfter (s)', "ReadArticle %", "ReadArticleAfter (s)",
                                        "PageBottom %", "PageBottomAfter (s)"],
                        datasets: [
                            {
                                fillColor: "rgba(231,104,102,0.2)",
                                strokeColor: "#e76866",
                                pointColor: "#e76866",
                                pointStrokeColor: "#e76866",
                                data: [arr['startReadingPercentage']*100, arr['startReadingAfterAVG'],
                                    arr['readArticlePercentage']*100, arr['readArticleAfterAVG'], arr['pageBottomPercentage']*100,
                                        arr['pageBottomAfterAVG']]
                            }
                        ]
                    };

                // Reduce the animation steps.
                var myLiveChartArticle = new Chart(ctx).Bar(startingData, {animationSteps: 30});



            }
        };
        xmlhttp.open("GET", "/sites/all/modules/custom/analytics01/infoArticle.php?q=" + str, true);
        xmlhttp.send();
    }
}
