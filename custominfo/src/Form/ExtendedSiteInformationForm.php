<?php

namespace Drupal\custominfo\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;


class ExtendedSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('system.site');
   	$form =  parent::buildForm($form, $form_state);
   	$form['site_information']['siteapikey'] = [
   	  '#type' => 'textfield',
   	  '#title' => t('Site API Key'),
   	  '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
   	  '#description' => t("Custom field to set the API Key"),
   	];
   	$form['actions']['submit']['#value'] = t('Update configuration');
   	return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $siteapikey = $form_state->getValue('siteapikey');
   	$config = \Drupal::config('system.site');
   	$system_site_key = $config->get('siteapikey');
   	if($siteapikey != $system_site_key) {
   	  \Drupal::messenger()->addMessage(t('Successfully updated Site API Key.'), 'status', TRUE);
   	}
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
   	$values = $form_state->getValues();
   	$this->config('system.site')
   	->set('siteapikey', $form_state->getValue('siteapikey'))
   	->save();
   	parent::submitForm($form, $form_state);
   }
 }