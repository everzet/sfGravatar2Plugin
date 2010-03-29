<?php

/*
 * This file is part of the sfGravatar2Plugin.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * gravatar implements gravatar API.
 *
 * @package    sfGravatar2Plugin
 * @subpackage libs
 * @author     Konstantin Kudryashov <ever.zet@gmail.com>
 * @version    1.0.0
 */
class gravatarApi
{
  protected $url_base = 'http://www.gravatar.com/avatar/';
  protected $url_secure_base = 'https://secure.gravatar.com/avatar/';
  protected $available_ratings = array('g', 'pg', 'r', 'x');
  protected $available_defaults = array('identicon', 'monsterid', 'wavatar', '404');
  protected $is_secure;

  protected $hash;
  protected $type;
  protected $size;
  protected $rating;
  protected $default;

  public function __construct($type = null, $size = null, $rating = null, $default = null,
                              $is_secure = null, $is_cacheable = null)
  {
    $this->setType(
      null !== $type ? $type : sfConfig::get('app_sf_gravatar2_plugin_image_type', 'png')
    );
    $this->setSize(
      null !== $size ? $size : sfConfig::get('app_sf_gravatar2_plugin_image_size', 80)
    );
    $this->setRating(
      null !== $rating ? $rating : sfConfig::get('app_sf_gravatar2_plugin_rating', 'g')
    );
    $this->setDefault(
      null !== $default ? $default : sfConfig::get('app_sf_gravatar2_plugin_default', null)
    );
    $this->setIsSecure(
      null !== $is_secure ? $is_secure : sfConfig::get('app_sf_gravatar2_plugin_secure', false)
    );
  }

  /**
   * Returns base URL of gravatar service
   *
   * @return string URL
   */
  public function getUrlBase()
  {
    return $this->isSecure() ? $this->url_secure_base : $this->url_base;
  }

  /**
   * Returns generated URL for avatar
   *
   * @param string $email E-Mail
   * @return string generated URL
   */
  public function getUrl($email = null)
  {
    if (null !== $email)
    {
      $this->setEmail($email);
    }

    $image = sprintf('%s.%s', $this->hash, null !== $this->type ? $this->type : 'png');

    return sprintf('%s%s?s=%d&r=%s%s',
      $this->getUrlBase(), $image, $this->size, $this->rating,
      null !== $this->default ? sprintf('&d=%s', $this->default) : ''
    );
  }

  /**
   * Sets the email hash
   *
   * @param string $hash E-Mail hash
   */
  public function setHash($hash)
  {
    $this->hash = $hash;
  }

  /**
   * Sets the email hash
   *
   * @param string $email E-Mail
   */
  public function setEmail($email)
  {
    $this->setHash(md5(strtolower($email)));
  }

  /**
   * Sets the image type
   *
   * @param string $type image type (png|jpg)
   */
  public function setType($type)
  {
    $this->type = $type;
  }

  /**
   * Returns the size of image
   *
   * @return integer size of image
   */
  public function getSize()
  {
    return intval($this->size);
  }

  /**
   * Set size of image
   *
   * @param integer $size image size
   */
  public function setSize($size)
  {
    $size = intval($size);

    if (0 < $size && 513 > $size)
    {
      $this->size = $size;
    }
    else
    {
      throw new Exception(sprintf('Size %d unavailable', $size));
    }
  }

  /**
   * Set rating of image
   *
   * @param string $rating one of P,G,PG,X
   */
  public function setRating($rating)
  {
    $rating = strtolower($rating);

    if (in_array($rating, $this->getAvailableRatings()))
    {
      $this->rating = $rating;
    }
    else
    {
      throw new Exception(sprintf('Rating "%s" unavailable', $rating));
    }
  }

  /**
   * Sets default URL if no avatar found
   *
   * @param string $default default URL
   */
  public function setDefault($default)
  {
    $default = strtolower($default);

    if (in_array($default, $this->getAvailableDefaults()))
    {
      $this->default = $default;
    }
    else
    {
      $this->default = urlencode($default);
    }
  }

  /**
   * Is connection to gravatar secure
   *
   * @return bool true if secure, false if not
   */
  public function isSecure()
  {
    return $this->is_secure;
  }

  /**
   * Set security status of connection to gravatar
   *
   * @param bool $is_secure yes to set secure, false in other way
   */
  public function setIsSecure($is_secure)
  {
    $this->is_secure = (bool)$is_secure;
  }

  /**
   * Returns list of available ratings
   *
   * @return array available ratings
   */
  public function getAvailableRatings()
  {
    return $this->available_ratings;
  }

  /**
   * Returns list of predefined defaults
   *
   * @return array predefined defaults
   */
  public function getAvailableDefaults()
  {
    return $this->available_defaults;
  }

}