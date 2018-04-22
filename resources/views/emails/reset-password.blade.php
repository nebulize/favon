<mjml>
  <mj-body background-color="#E1E8ED">
    <mj-raw>
      <!-- Header -->
    </mj-raw>
    <mj-section padding-bottom="0px" background-color="white">
      <mj-column width="100%">
        <mj-image src="https://favon.co/images/favon.png" width="160"></mj-image>
        <mj-divider padding-top="20" padding-bottom="0px" padding-left="0px" padding-right="0px" border-width="1px" border-color="#f8f8f8"></mj-divider>
      </mj-column>
    </mj-section>
    <mj-section padding-bottom="0px" background-color="#fcfcfc">
      <mj-column width="100%">
        <mj-text align="left" font-size="20px" color="grey" font-family="Helvetica Neue" font-weight="200">Hello!</mj-text>
        <mj-text align="left" font-size="16px" color="grey" font-family="Helvetica Neue" font-weight="200">You are receiving this email because we received a password reset request for your account.</mj-text>
        <mj-button href="{{ route('password.reset', $token) }}" font-family="Helvetica" background-color="#ff3f7a" color="white">
          Reset Password
        </mj-button>
        <mj-text align="left" font-size="16px" color="grey" font-family="Helvetica Neue" font-weight="200">If you did not request a password reset, no further action is required.</mj-text>
        <mj-divider padding-top="20" padding-bottom="0px" padding-left="0px" padding-right="0px" border-width="1px" border-color="#f8f8f8"></mj-divider>
      </mj-column>
    </mj-section>
  </mj-body>
</mjml>
