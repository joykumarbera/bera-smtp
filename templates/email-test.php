<div class="wrap">
    <h3>Send a test email</h3>
    <form action="<?php echo $form_action ?>" method="POST">
        <input type="hidden" name="action" value="<?php echo $action ?>">
        <?php wp_nonce_field( $action, $nounce_name ) ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label>Email</label>
                    </th>
                    <td>
                        <input name="bera_test_email" type="email" class="regular-text">
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button('Test Configaration') ?>
    </form>
</div>