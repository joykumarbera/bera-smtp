<div class="wrap">
    <nav>
    </nav>

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
                        <input name="bera_smtp[host]" type="text" value="" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Username</label>
                    </th>
                    <td>
                        <input name="bera_smtp[username]" type="text" value="" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Password</label>
                    </th>
                    <td>
                        <input name="bera_smtp[password]" type="text" value="" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Auth</label>
                    </th>
                    <td>
                        <select name="bera_smtp[auth]">
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Encryption</label>
                    </th>
                    <td>
                        <select name="bera_smtp[encryption]">
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="none">None</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP Port</label>
                    </th>
                    <td>
                        <input name="bera_smtp[port]" type="number" value="" class="small-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP From</label>
                    </th>
                    <td>
                        <input name="bera_smtp[from]" type="email" value="" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>SMTP From Name</label>
                    </th>
                    <td>
                        <input name="bera_smtp[from_name]" type="text" value="" class="regular-text">
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button() ?>
    </form>
</div>