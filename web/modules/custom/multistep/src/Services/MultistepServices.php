<?php

namespace Drupal\multistep\Services;

/**
 * Class MultistepServices.
 *
 * @package Drupal\multistep\Services
 */
class MultistepServices {

  /**
   * Get Cities.
   */
  public function getCityList() {
    $cities = [
      'bogota' => t('Bogotá'),
      'medellin' => t('Medellín'),
      'cali' => t('Cali'),
    ];
    return $cities;
  }

  /**
   * Generate Password.
   */
  public function generatePassword() {

    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = [];
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);

  }

  /**
   * Save User.
   */
  public function saveUser($name, $password, $mail, $birthday) {

    $ids = \Drupal::entityQuery('user')
        ->condition('name', $name)
        ->range(0, 1)
        ->execute();
    if(!empty($ids)){
      //then this name already exists
      $random = rand(10,1000);
      $name = $name . $random;
    }

    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $user = \Drupal\user\Entity\User::create();
    $user->setPassword($password);
    $user->enforceIsNew();
    $user->setEmail($mail);
    $user->setUsername($name);
    $user->set("init", 'mail');
    $user->set("langcode", $language);
    $user->set("preferred_langcode", $language);
    $user->set("preferred_admin_langcode", $language);
    $user->activate();
    $user->save();

    return $name;

  }
}
