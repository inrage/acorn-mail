<?php

namespace inRage\AcornMail;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use Roots\Acorn\Application;

class AcornMail
{
    protected Collection $config;

    public function __construct(
        protected readonly Application $app
    ) {
        $this->config = Collection::make($this->app->config->get('wp-mail.smtp'))
            ->merge($this->app->config->get('wp-mail.from'))
            ->merge($this->app->config->get('wp-mail.default')
            );

        if (! $this->isConfigured()) {
            return;
        }

        $this->overrideWordPressMailer();
    }

    protected function fromName(): string
    {
        $name = $this->config->get('name');

        return $name && ! Str::is($name, 'Example')
            ? $name
            : get_bloginfo('name', 'display');
    }

    protected function fromAddress(): string
    {
        $address = $this->config->get('address');

        if ($address && ! Str::is($address, 'hello@example.com')) {
            return $address;
        }

        $domain = parse_url(home_url(), PHP_URL_HOST);

        return "noreply@{$domain}";
    }

    public static function make(Application $app): self
    {
        return new static($app);
    }

    private function isConfigured(): bool
    {
        return match ($this->config->get(0)) {
            'smtp' => $this->config->get('host') && $this->config->get('port'),
            'smtps' => $this->config->get('host')
                && $this->config->get('port')
                && $this->config->get('username')
                && $this->config->get('password'),
            'native' => true,
            default => false,
        };
    }

    private function overrideWordPressMailer(): void
    {
        if (! in_array($this->config->get(0), ['smtp', 'smtps'])) {
            return;
        }

        add_filter('phpmailer_init', function (PHPMailer $mail) {
            $mail->isSMTP();

            $mail->SMTPAuth = $this->config->get(0) === 'smtps';
            $mail->Host = $this->config->get('host');
            $mail->Port = $this->config->get('port');
            $mail->Timeout = $this->config->get('timeout', $mail->Timeout);

            if ($this->config->get(0) === 'smtps') {
                $mail->SMTPSecure = $this->config->get('encryption', $mail->SMTPSecure);
                $mail->Username = $this->config->get('username');
                $mail->Password = $this->config->get('password');
            }

            $mail->setFrom(
                $this->fromAddress(),
                $this->fromName()
            );
        });
    }
}
