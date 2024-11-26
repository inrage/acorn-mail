# WordPress Acorn Mail

Acorn Mail is an Acorn provider for WordPress. It allows you to send emails using an SMTP configuration.

## Requirements

- PHP 8.3 or higher
- Acorn 4.0 or higher

## Installation

```bash
composer require inrage/acorn-mail
```

## Getting Started

Start by publishing the configuration file:

```bash
wp acorn vendor:publish --tag=acorn-mail
```

Then, configure the SMTP settings in the `.env` file:

```dotenv
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
```

## Usage

### Native

The `native` mailer uses the native PHP `mail` function to send emails.

```dotenv
MAIL_MAILER=native
```

### SMTP

The `smtp` mailer uses an SMTP server without authentification to send emails. For example, you can use MailHog to test emails locally.

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```

### SMTPS

The `smtps` mailer uses an SMTP server with authentification to send emails.

```dotenv
MAIL_MAILER=smtps
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=123456ABCDEF
MAIL_PASSWORD=123456ABCDEF
MAIL_ENCRYPTION=tls
```

Once the configuration is set up, you can send emails using the `wp-cli` command:

```bash
wp eval 'wp_mail("john_doe@example", "Test e-mail from " . home_url(), "This is a test");'
```

## Bugs and Issues

If you encounter any bugs or issues, feel free to [open an issue](https://github.com/inrage/acorn-mail/issues).

## License

Acorn Mail is open-sourced software licensed under the [MIT license](LICENSE.md).
