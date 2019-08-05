<?php

namespace system;
use Carbon_Fields\Block;
use Carbon_Fields\Field;

class Carbon
{

    public function __construct()
    {
      add_action( 'carbon_fields_register_fields', [ $this, 'load_fields' ] );
    }

    public function load_fields()
    {

      Block::make('Hero Section')
        ->add_fields(array(
            Field::make('separator', 'hh_separator', 'Home Hero'),
            Field::make('rich_text', 'hh_content', 'Hero Content'),
            Field::make('image', 'hh_background_image', 'Background Image')
                ->set_value_type('id')
                ->set_type('image'),
        ))
        ->set_category('ABC Financial')
        ->set_render_callback( [ __CLASS__, 'render_hero_section' ] );

    }

    public static function render_hero_section( $fields, $attributes, $content )
    {
      $section_class = array('section', 'section-hero section--fullscren');
      if (isset($attributes['className'])) {
          array_push($section_class, $attributes['className']);
      }
      ?>
      <section class="<?php echo implode(" ", $section_class) ?>">

          <?php if (isset($fields['hh_background_image'])) { ?>
              <div class="section__background">
                  <?php echo wp_get_attachment_image($fields['hh_background_image'], 'background-half', "", array("class" => "img-fluid section__backgroundimage"));  ?>
              </div>
          <?php } ?>

          <div class="container">
              <?php
              if (!empty($fields['hh_content'])) {
                  echo $fields['hh_content'];
              }
              ?>
          </div>
      </section>
      <?php
    }

}
