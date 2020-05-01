<?php

namespace Drupal\img_aspect_ratio_validation\Plugin\Field\FieldType;

use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

/**
 * Plugin implementation of the 'image' field type.
 *
 * @FieldType(
 *   id = "image",
 *   label = @Translation("Image"),
 *   description = @Translation("This field stores the ID of an image file as an integer value."),
 *   category = @Translation("Reference"),
 *   default_widget = "image_image",
 *   default_formatter = "image",
 *   column_groups = {
 *     "file" = {
 *       "label" = @Translation("File"),
 *       "columns" = {
 *         "target_id", "width", "height"
 *       },
 *       "require_all_groups_for_translation" = TRUE
 *     },
 *     "alt" = {
 *       "label" = @Translation("Alt"),
 *       "translatable" = TRUE
 *     },
 *     "title" = {
 *       "label" = @Translation("Title"),
 *       "translatable" = TRUE
 *     },
 *   },
 *   list_class = "\Drupal\file\Plugin\Field\FieldType\FileFieldItemList",
 *   constraints = {"ReferenceAccess" = {}, "FileValidation" = {}}
 * )
 */
class ImgAspectRatioValidationItem extends ImageItem {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    $settings = [
      'aspect_ratio' => '',
    ] + parent::defaultFieldSettings();

    unset($settings['description_field']);
    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    // Get base form from FileItem.
    $element = parent::fieldSettingsForm($form, $form_state);
    $settings = $this->getSettings();
    $aspect_ratio = '';
    if (!empty($settings['aspect_ratio'])) {
      $aspect_ratio = $settings['aspect_ratio'];
    }
    $element['aspect_ratio'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Aspect Ratio'),
      '#default_value' => $aspect_ratio,
      '#attributes' => ['placeholder' => "W:H"],
      '#min' => 1,
    ];
    return $element;
  }

}
