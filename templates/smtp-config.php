<h3>Bera SMTP</h3>
<div class="wrap">
    <h4>SMTP configaration</h4>
    <form action="<?php echo $form_action ?>" method="POST">
        <input type="hidden" name="action" value="<?php echo $action ?>">
        <?php wp_nonce_field( $action, $nounce_name ) ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label>SMTP Host</label>
                    </th>
                    <td>
                        <input name="bera_smtp[host]" type="text" value="<?php echo ( isset($current_form_data) ) ? $current_form_data['host'] : '' ?>" class="regular-text">
                        <br>
                        <small>Enter host name</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Username</label>
                    </th>
                    <td>
                        <input name="bera_smtp[username]" type="text" value="<?php echo ( isset($current_form_data) ) ? $current_form_data['username'] : '' ?>" class="regular-text">
                        <br>
                        <small>Enter SMTP username</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Password</label>
                    </th>
                    <td>
                        <input name="bera_smtp[password]" type="password" value="<?php echo ( isset($current_form_data) ) ? $current_form_data['password'] : '' ?>" class="regular-text">
                        <br>
                        <small>Enter SMTP password</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Auth</label>
                    </th>
                    <td>
                        <select name="bera_smtp[auth]">
                            <option value="yes" <?php echo (isset($current_form_data) && $current_form_data['auth'] == 'yes') ? 'selected': '' ?>>Yes</option>
                            <option value="no" <?php echo (isset($current_form_data) && $current_form_data['auth'] == 'no') ? 'selected': '' ?>>No</option>
                        </select>
                        <br>
                        <small>Required authentication?</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Encryption</label>
                    </th>
                    <td>
                        <select name="bera_smtp[encryption]">
                            <option value="tls" <?php echo (isset($current_form_data) && $current_form_data['encryption'] == 'tls') ? 'selected': '' ?>>TLS</option>
                            <option value="ssl" <?php echo (isset($current_form_data) && $current_form_data['encryption'] == 'ssl') ? 'selected': '' ?>>SSL</option>
                            <option value="none" <?php echo (isset($current_form_data) && $current_form_data['encryption'] == 'none') ? 'selected': '' ?>>None</option>
                        </select>
                        <br>
                        <small>Choose encryption type</small>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Port</label>
                    </th>
                    <td>
                        <input name="bera_smtp[port]" type="number" value="<?php echo ( isset($current_form_data) ) ? $current_form_data['port'] : '' ?>" class="small-text">
                        <br>
                        <small>Enter 465 for SSL or enter 587 for TLS</small>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button() ?>
    </form>
</div>