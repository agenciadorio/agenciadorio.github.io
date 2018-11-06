<?php  
/**
*
*   Theme: Sonik
*   File: qt-user-picture.php
*   Role: add user image uploader
*   @author : QantumThemes <info@qantumthemes.com>
*
*
**/




/* = User special fields: picture
=============================================*/
if ( ! function_exists( 'qt_add_custom_user_profile_fields' ) ) {

function qt_add_custom_user_profile_fields( $user ) {
    $value = esc_attr( get_the_author_meta( 'picture', $user->ID ) );
?>
    <h3><?php esc_attr__('Author picture url', "qt-extensions-suite"); ?></h3>

    <table class="form-table">
        <tr>
            <th>
                <label for="picture"><?php _e('Custom picture url', "qt-extensions-suite"); ?>
            </label></th>
            <td>

                <input type="text" name="picture" id="picture" value="<?php echo esc_attr( $value ); ?>" class="regular-text" /><br />
                <span class="description"><?php esc_attr__('Insert a full picture url (http:// ...... )', "qt-extensions-suite"); ?></span>
                <div class="qt-author-pic">
                    
                    <?php if('' != $value) { ?>
                    <img src="<?php echo esc_attr(esc_url($value)); ?>" class="qt-admin-thumbnail" alt="user picture">
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
<?php 


}}

add_action( 'show_user_profile', 'qt_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'qt_add_custom_user_profile_fields' );




if ( ! function_exists( 'qt_save_custom_user_profile_fields' ) ) {
function qt_save_custom_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
     return FALSE;
    $allowedTypes = array('image/gif', 'image/jpeg', 'image/png');
    update_user_meta( $user_id, 'picture', $_POST['picture'] );
}}


add_action( 'personal_options_update', 'qt_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'qt_save_custom_user_profile_fields' );


