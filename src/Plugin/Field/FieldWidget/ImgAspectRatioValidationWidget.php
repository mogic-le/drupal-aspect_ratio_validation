<?php

namespace Drupal\img_aspect_ratio_validation\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Plugin\Field\FieldWidget\ImageWidget;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Override plugin of the 'image_image' widget.
 *
 * We have to fully override standard field widget, so we will keep original
 * label and formatter ID.
 *
 * @FieldWidget(
 *   id = "image_image",
 *   label = @Translation("Image"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class ImgAspectRatioValidationWidget extends ImageWidget {

  /**
   * Container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  private $container;

  /**
   * Entity repository service instance.
   *
   * @var \Drupal\Core\Entity\EntityRepository
   */
  protected $entityRepository;

  /**
   * Renderer instance.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * EntityType manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Image style storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $imageStyleStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct($pluginId, $pluginDefinition, FieldDefinitionInterface $fieldDefinition, array $settings, array $thirdPartySettings, ContainerInterface $container) {
    parent::__construct($pluginId, $pluginDefinition, $fieldDefinition, $settings, $thirdPartySettings, $container->get('element_info'));

    $this->container = $container;
    $this->entityRepository = $container->get('entity.repository');
    $this->renderer = $container->get('renderer');
    $this->entityTypeManager = $container->get('entity_type.manager');
    $this->imageStyleStorage = $this->entityTypeManager->getStorage('image_style');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
    return new static(
      $pluginId,
      $pluginDefinition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $formState) {
    $element = parent::formElement($items, $delta, $element, $form, $formState);
    $fieldSettings = $this->getFieldSettings();
    if (isset($fieldSettings['aspect_ratio'])) {
      $element['#upload_validators']['file_validate_image_aspect_ratio_validation'] = [$fieldSettings['aspect_ratio']];
    }
    return $element;
  }

}
