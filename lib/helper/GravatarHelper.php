<?php

/**
 * Returns a gravatar image path for a given email
 *
 * @param string $email email
 * @param string $type type of image (png, jpg)
 * @param string $size size of image (1..512)
 * @param string $rating rating of avatar (p, g, pg, x)
 * @return string image tag
 */
function gravatar_image_path($email, $type = null, $size = null, $rating = null)
{
  $api = new gravatarApi($type, $size, $rating);

  return $api->getUrl($email);
}

/**
 * Returns a image tag with url to gravatar for a given email
 *
 * @param string $email email
 * @param string $type type of image (png, jpg)
 * @param string $size size of image (1..512)
 * @param string $rating rating of avatar (p, g, pg, x)
 * @param string $alt alternative text for image
 * @return string image tag
 */
function gravatar_image_tag($email, $type = null, $size = null, $rating = null, $alt = 'Gravatar')
{
  return image_tag(gravatar_image_path($email, $type, $size, $rating), array(
    'alt'     => $alt,
    'width'   => null !== $size ? $size : sfConfig::get('app_sf_gravatar2_plugin_image_size', 80),
    'height'  => null !== $size ? $size : sfConfig::get('app_sf_gravatar2_plugin_image_size', 80),
    'class'   => 'gravatar'
  ));
}
