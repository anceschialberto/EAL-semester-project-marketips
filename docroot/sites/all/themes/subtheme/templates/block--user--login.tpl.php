<?php
$elements = drupal_get_form('user_login_block');
$rendered = drupal_render($elements);

$output = '
<ul class="nav navbar-nav navbar-right">
	<li class="leaf">
    <a href="/user/register" title="Create a new user account.">Sign Up</a>
  </li>
	<li class="expanded dropdown">
    <a href="#" title data-target="#" class="dropdown-toggle nolink" data-toggle="dropdown" >
			Sign in
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="padding: 15px; min-width: 250px;">
			<li class="first leaf">
				<div class="row">
					<div class="col-md-12">';

$output  .= '<form action="' . $elements['#action'] . '" method="' . $elements['#method'] .
            '" id="' . $elements['#id'] .
            '" accept-charset="UTF-8"><div>';
$output .= $elements['name']['#children'];
$output .= $elements['pass']['#children'];
$output .= $elements['form_build_id']['#children'];
$output .= $elements['form_id']['#children'];
$output .= $elements['actions']['#children'];
$output .= '<div class="sign-in-links">' . $elements['links']['#children'] . '</div>';
$output .= '</div></form>';

$output .= '
          </div>
        </div>
      </li>
      <li class="divider">&nbsp;</li>
      <li>
      <input class="btn btn-primary btn-block" id="sign-in-google" type="button" value="Sign In with Google" />
      <input class="btn btn-primary btn-block" id="sign-in-twitter" type="button" value="Sign In with Twitter" />
      </li>
    </ul>
  </li>
</ul>';

print $output;

?>
