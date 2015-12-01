<?php
/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 *
 * Variables:
 * - $css: An array of CSS files for the current page.
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or
 *   'rtl'.
 * - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
 * - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
 * - $head_title: A modified version of the page title, for use in the TITLE
 *   tag.
 * - $head_title_array: (array) An associative array containing the string parts
 *   that were used to generate the $head_title variable, already prepared to be
 *   output as TITLE tag. The key/value pairs may contain one or more of the
 *   following, depending on conditions:
 *   - title: The title of the current page, if any.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site, if any, and if there is no title.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $page_top: Initial markup from any modules that have altered the
 *   page. This variable should always be output first, before all other dynamic
 *   content.
 * - $page: The rendered page content.
 * - $page_bottom: Final closing markup from any modules that have altered the
 *   page. This variable should always be output last, after all other dynamic
 *   content.
 * - $classes String of classes that can be used to style contextually through
 *   CSS.
 *
 * @see bootstrap_preprocess_html()
 * @see template_preprocess()
 * @see template_preprocess_html()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces;?>>
<head profile="<?php print $grddl_profile; ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <!-- HTML5 element support for IE6-8 -->
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <?php print $scripts; ?>

  <!-- INIZIO PARTE DI PARSE -->
  <script src="//www.parsecdn.com/js/parse-1.6.7.min.js"></script>

  <script type="text/javascript">
    Parse.initialize("P60EfTUuOZoeZyD2qSLpOrc8DWwUk2YjEqU2HY1R", "JlgM5RtBO3nYKd9YqvFho8Su9xppRmwcRxRpaeIV");

    //comandi parse
    // Simple syntax to create a new subclass of Parse.Object.
    var Visualizzazione = Parse.Object.extend("visualizzazione");

    // Create a new instance of that class.
    var visualizzazione = new Visualizzazione();
    //visualizzazione.set({ parseId: Drupal.settings.parse.parseId.toString()});
    visualizzazione.set({ user: Drupal.settings.parse.uid.toString()});
    visualizzazione.set({ date: new Date().getTime()});
    visualizzazione.set({ url: window.location.pathname});
    console.log("Path " + window.location.pathname);
    visualizzazione.set({ tag: Drupal.settings.parse.tag})

    visualizzazione.save(null, {
      success: function(visualizzazione) {
        // Execute any logic that should take place after the object is saved.
        if(Drupal.settings.parse.debug){
            console.log('New object created with objectId: ' + visualizzazione.id);
        }
      },
      error: function(visualizzazione, error) {
        // Execute any logic that should take place if the save fails.
        // error is a Parse.Error with an error code and message.
        if(Drupal.settings.parse.debug){
            alert('Failed to create new object, with error code: ' + error.message);
        }
      }
    });
  </script>



  <script type="text/javascript">
    //Script per vedere quanto hai letto di un articolo
    jQuery(function($) {
      // Debug flag
      var debugMode = false;

      // Default time delay before checking location
      var callBackTime = 100;

      // # px before tracking a reader
      var readerLocation = 250;

      // Set some flags for tracking & execution
      var timer = 0;
      var scroller = false;
      var endContent = false;
      var didComplete = false;

      // Set some time variables to calculate reading time
      var startTime = new Date();
      var beginning = startTime.getTime();
      var totalTime = 0;

      // Get some information about the current page
      var pageTitle = document.title;

      // Track the article load
      if (!debugMode) {
          var Reading = Parse.Object.extend("Reading");

          var reading = new Reading();
          reading.set({pageLoaded: window.location.pathname});
          reading.set({ user: Drupal.settings.parse.uid.toString()});
          reading.set({title: Drupal.settings.parse.title});
          reading.save(null, {
            success:function(reading){
              if(Drupal.settings.parse.debug){
                console.log('New Reading created with objectId: ' + reading.id);
              }
            },
            error: function(reading, error){
              if(Drupal.settings.parse.debug){
                alert('Failed to create new object, with error code: ' + error.message);
              }
            }
          });
      } else {
          alert('The page has loaded. Woohoo.');
      }

      // Check the location and track user
      function trackLocation() {
          bottom = $(window).height() + $(window).scrollTop();
          height = $(document).height();

          // If user starts to scroll send an event
          if (bottom > readerLocation && !scroller) {
              currentTime = new Date();
              scrollStart = currentTime.getTime();
              timeToScroll = Math.round((scrollStart - beginning) / 1000);
              if (!debugMode) {
                reading.set({startReading: timeToScroll});
                reading.save(null, {
                  success:function(reading){
                    if(Drupal.settings.parse.debug){
                      console.log('Object reading ' + reading.id + " read in " + timeToScroll);
                    }
                  },
                  error: function(reading, error){
                    if(Drupal.settings.parse.debug){
                      alert('Failed to create new object, with error code: ' + error.message);
                    }
                  }
                });
              } else {
                  alert('started reading ' + timeToScroll);
              }
              scroller = true;
          }

          // If user has hit the bottom of the content send an event (la segnalazione parte un po' prima ad essere sinceri)
          if (bottom >= $('#block-system-main').scrollTop() + $('#block-system-main').innerHeight() && !endContent) {
              currentTime = new Date();
              contentScrollEnd = currentTime.getTime();
              timeToContentEnd = Math.round((contentScrollEnd - scrollStart) / 1000);
              if (!debugMode) {
                  reading.set({readArticle: timeToContentEnd});
                  reading.save(null, {
                    success:function(reading){
                      if(Drupal.settings.parse.debug){
                        console.log('Object bottom ' + reading.id + " read in " + timeToContentEnd);
                      }
                    },
                    error: function(reading, error){
                      if(Drupal.settings.parse.debug){
                        alert('Failed to create new object, with error code: ' + error.message);
                      }
                    }
                  });
              } else {
                  alert('end content section '+timeToContentEnd);
              }
              endContent = true;
          }

          // If user has hit the bottom of page send an event
          if (bottom >= height && !didComplete) {
              currentTime = new Date();
              end = currentTime.getTime();
              totalTime = Math.round((end - scrollStart) / 1000);
              if (!debugMode) {
                  if (totalTime < 60) {
                      reading.set({type: 'Scanner'});
                      reading.save(null, {
                        success:function(reading){
                          if(Drupal.settings.parse.debug){
                            console.log('Object bottom ' + reading.id + " type read Scanner");
                          }
                        },
                        error: function(reading, error){
                          if(Drupal.settings.parse.debug){
                            alert('Failed to create new object, with error code: ' + error.message);
                          }
                        }
                      });
                  } else {
                      reading.set({type: 'Reader'});
                      reading.save(null, {
                        success:function(reading){
                          if(Drupal.settings.parse.debug){
                            console.log('Object bottom ' + reading.id + " type read Reader");
                          }
                        },
                        error: function(reading, error){
                          if(Drupal.settings.parse.debug){
                            alert('Failed to create new object, with error code: ' + error.message);
                          }
                        }
                      });
                  }
                  reading.set({pageBottom: totalTime});
                  reading.save(null, {
                    success:function(reading){
                      if(Drupal.settings.parse.debug){
                        console.log('Object bottom ' + reading.id + " read in " + totalTime);
                      }
                    },
                    error: function(reading, error){
                      if(Drupal.settings.parse.debug){
                        alert('Failed to create new object, with error code: ' + error.message);
                      }
                    }
                  });
              } else {
                  alert('bottom of page '+totalTime);
              }
              didComplete = true;
          }
      }

      // Track the scrolling and track location
      $(window).scroll(function() {
          if (timer) {
              clearTimeout(timer);
          }

          // Use a buffer so we don't call trackLocation too often.
          timer = setTimeout(trackLocation, callBackTime);
      });
  });
  </script>
  <!-- FINE PARTE DI PARSE -->

</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
  </div>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
