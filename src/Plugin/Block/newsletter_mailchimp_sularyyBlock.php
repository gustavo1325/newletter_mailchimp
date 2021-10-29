<?php

namespace Drupal\newsletter_mailchimp_sularyy\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a 'newsletter_mailchimp_sularyy.
 *
 * @Block(
 *   id = "newsletter_mailchimp_sularyy",
 *   admin_label = @Translation("newsletter_mailchimp_sularyy block"),
 *   category = @Translation("Sularyy"),
 * )
 */

class newsletter_mailchimp_sularyyBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */

  public function build() {
    return [
      '#theme' => 'newsletter',
      '#form' =>  \Drupal::formBuilder()->getForm('Drupal\newsletter_mailchimp_sularyy\Form\addform'),
      '#attached' => [
        'library' => [
          'newsletter_mailchimp_sularyy/newsletter_sularyy',
        ],
      ],
    ];
  }

}
