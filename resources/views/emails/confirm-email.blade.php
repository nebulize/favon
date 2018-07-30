<mjml>
  <mj-body background-color="#E1E8ED">
    <mj-section padding-bottom="0px" background-color="white">
      <mj-column width="100%">
        <mj-image src="https://favon.co/images/favon.png" width="160"></mj-image>
        <mj-divider padding-top="20" padding-bottom="0px" padding-left="0px" padding-right="0px" border-width="1px" border-color="#f8f8f8"></mj-divider>
      </mj-column>
    </mj-section>
    <mj-section padding-bottom="0px" background-color="#fcfcfc">
      <mj-column width="100%">
        <mj-text align="center" font-size="20px" color="grey" font-family="Helvetica Neue" font-weight="200">Thanks for signing up to Favon!</mj-text>
        <mj-text align="center" font-size="16px" color="grey" font-family="Helvetica Neue" font-weight="200">Please confirm your email address to complete your registration.</mj-text>
        <mj-button href="{{ route('auth.confirm-email', $user->email_token) }}" font-family="Helvetica" background-color="#ff3f7a" color="white">
          Confirm Email
        </mj-button>
        <mj-divider padding-top="20" padding-bottom="0px" padding-left="0px" padding-right="0px" border-width="1px" border-color="#f8f8f8"></mj-divider>
      </mj-column>
    </mj-section>
  </mj-body>
</mjml>
