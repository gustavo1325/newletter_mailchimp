<?php

namespace Drupal\newsletter_mailchimp_sularyy\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Defines newsletter_mailchimp_sularyyController class.
 */
class newsletter_mailchimp_sularyyController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */

  public function content(){
    $form = \Drupal::formBuilder()->getForm('Drupal\newsletter_mailchimp_sularyy\Form\addform');

    $contenido[] = [
      '#theme' => 'newsletter_mailchimp',
      //'#test_var' => t('Esto es una prueba'),
      '#form' => $form,
    ];

    return $contenido;
  }


}
