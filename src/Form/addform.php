<?php

namespace Drupal\newsletter_mailchimp_sularyy\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\newsletter_mailchimp_sularyy\Plugin\WebformHandler;//clase CREADA

/**
 * Implements an example form.
 */
class addform extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'newsletter_mailchimp_sularyy_addform';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['nom'] = array(
      '#type' => 'textfield',
      //'#default_value'=>t('nom'),
      '#placeholder'=>t('Nom'),
      '#size' => 60,
      '#maxlength'  => 128,
      '#required' => TRUE,
      '#attributes' => array(
        'class' => array('newsletter_sularyy_nom')
      ),
      );


    $form['email'] = array(
      '#type' => 'email',
      /*'#default_value'=>t('Email'),*/
      '#placeholder'=>t('Email'),
      //'#title' => $this->t('Email'),
      '#attributes' => array(
        'class' => array('newsletter_sularyy_email')
      ),
      );

      $form['politica_privacidad'] = array(
        '#type' => 'checkboxes',
        '#options' => array('Acepto la politica de privacidad' => $this->t('Accepto la política de privacitat de dades')),
        '#attributes' => array(
          'class' => array('newsletter_sularyy_checkboxes')
        ),
      );

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('DESA'),
          '#button_type' => 'primary',
          '#attributes' => array(
            'class' => array('newsletter_sularyy_bottom')
          ),
          '#attributes' => array(
            'id' => array('otroidposible')
          ),
        ];
       // honeypot_add_form_protection($form, $form_state, array('honeypot', 'time_restriction'));
        return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  /*  if (strlen($form_state->getValue('phone_number')) < 3) {
      $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
    }
    if (strlen($form_state->getValue ('politica_privacidad')) != 1) {
     \Drupal::messenger()->addMessage(t('debe aceptar la politica de privacidad'));
  }*/
  $values = $form_state->getValues();
  $nombre = $values['nom'];
  //VALIDA NOM
  if(empty($nombre) or $nombre == 'nom'){
        $form_state->setErrorByName('nom', $this->t("Escriva el seu nom."));

  }

  //VALIDA CHEXBOX

  if($values['politica_privacidad']['Acepto la politica de privacidad'] !== 'Acepto la politica de privacidad'){
             //\Drupal::messenger()->addMessage(t('debe aceptar la politica de privacidad'));
             $form_state->setErrorByName('politica_privacdad', $this->t("Ha d'acceptar la política de privacitat."));

          }
}

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration() {
      return [];
    }
  
    const MAILCHIMP_API_KEY = 'dfcb07a432d7cf9f2b0cb6b02769c310-us5'; // see https://mailchimp.com/help/about-api-keys
    const LIST_ID = '747bd72fff'; // see https://3by400.com/get-support/3by400-knowledgebase?view=kb&kbartid=6
    const SERVER_LOCATION = 'us5'; // the string after the '-' in your MAILCHIMP_API_KEY f.e. us4
  
  

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    /*$newMail = \Drupal::service('plugin.manager.mail');
    //$params['password'] = $password;
    //$params['email'] = $userEmail;
    $email= $form_state->getValue('email');
    $params['email'] = $email;
    $params['nom'] = $nom;
    //$to obtiene le email del sitio
  //  $to =  \Drupal::config('system.site')->get('mail');
    $to =  'gust.castilla@gmail.com';
    //lenguaje por defecto del sitio
    $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $module='newsletter_sularyy';
    $key='newsletter_sularyy';

    $newMail->mail($module, $key, $to, $langcode, $params, $reply = NULL, $send = TRUE);
    //dpm($newMail);
    if (!$newMail) {
      $errorMessage = error_get_last()['message'];
      \Drupal::messenger()->addMessage(t("Les dades no s'han guardat."));
      $form_state->setRedirect('<front>');
    }else{
      \Drupal::messenger()->addMessage(t('Dades guardades correctament.'));}*/


      //$values = $webform_submission->getData();
     // $email = strtolower($values['email']);
      //$name = $values['nom'];
      //$last_name = $values['last_name'];

      $email=$form_state->getValue('email');
      $name=$form_state->getValue('nom');
      // The data to send to the API
      $postData = array(
        "email_address" => "$email",
        "status" => "subscribed",
        'tags'  => array('Newsletter'),
        "merge_fields" => array(
          "FNAME" => "$name"
        )
      );
  
      // Setup cURL
      // To get the correct dataserver, see the url of your mailchimp back-end, mine is https://us20.admin.mailchimp.com/account/api/
      $ch = curl_init('https://'.self::SERVER_LOCATION.'.api.mailchimp.com/3.0/lists/'.self::LIST_ID.'/members/');
      curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
          'Authorization: apikey '.self::MAILCHIMP_API_KEY,
          'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
      ));
  
      // Send the request
      $response = curl_exec($ch);
      $readable_response = json_decode($response);
      if(!$readable_response) {
        \Drupal::logger('Mailchimp_subscriber')->error($readable_response->title.': '.$readable_response->detail .'. Raw values:'.print_r($values));
        \Drupal::messenger()->addError('Something went wrong. Please contact your webmaster.');
      }
      if($readable_response->status == 403) {
        \Drupal::logger('Mailchimp_subscriber')->error($readable_response->title.': '.$readable_response->detail .'. Raw values:'.print_r($values));
        \Drupal::messenger()->addError('Something went wrong. Please contact your webmaster.');
      }
      if($readable_response->status == 'subscribed') {
        \Drupal::messenger()->addStatus('You are now successfully subscribed.');
      }
      if($readable_response->status == 400) {
        if($readable_response->title == 'Member Exists') {
          \Drupal::messenger()->addWarning('You are already subscribed to this mailing list.');
        }
      }
  
      return true;
     

      //$form_state->setRedirect('<front>');
  }
}
