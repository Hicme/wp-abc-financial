<?php
namespace system;
use Carbon_Fields\Block;
use Carbon_Fields\Field;
class Carbon
{
  public function __construct()
  {
    add_action('carbon_fields_register_fields', [$this, 'load_fields']);
  }
  public function load_fields()
  {
    Block::make('Page Content')
      ->add_fields(array(
        Field::make('separator', 'hh_separator', 'Page Content'),
        Field::make( 'media_gallery', 'gallery', __( 'Images', 'wpabcf' ) )
          ->set_type( [ 'image' ] ),
        Field::make( 'complex', 'repeter', __( 'Blocks', 'wpabcf' ) )
          ->add_fields([
            Field::make( 'text', 'title', __( 'Block Title', 'wpabcf' ) ),
            Field::make( 'rich_text', 'content', __( 'Block Content', 'wpabcf' ) )
          ])
      ))
      ->set_category('ABC Financial')
      ->set_render_callback([__CLASS__, 'render_section']);
  }
  public static function render_section($fields, $attributes, $content)
  {
    $section_class = array('section', 'section-hero section--fullscren');
    if (isset($attributes['className'])) {
      array_push($section_class, $attributes['className']);
    }

    if( $fields['gallery'] ) {
      ?>
      <div class="section--col col-2">
        <ul class="image-gallery row">
          <?php
            foreach( $fields['gallery'] as $image ){
              ?>
                <li class="image-gallery__item col-2">
                  <?php echo wp_get_attachment_image( $image, 'large' ); ?>
                </li>
              <?php
            }
          ?>
        </ul>
      </div>
      <?php
    }
    ?>

    <div class="section--col col-2">
      <div class="overflow-text">
        <?php
          if( $fields['repeter'] ){
            $total = count( $fields['repeter'] );
            foreach( $fields['repeter'] as $item => $block ){
              ?>
              <h3>
                <?php echo $block['title']; ?>
              </h3>
              <?php
                echo $block['content'];
                if( $item < $total - 1 ){
                  echo '<hr>';
                }
            }
          }
        ?>
      </div>
    </div>
  <?php
  }
}