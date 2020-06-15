<?php

namespace App\Playbooks;

use App\Site;

class SiteCertRequestPlaybook extends Playbook
{
    /**
     * The displayable name of the playbook.
     *
     * @var string
     */
    public $name = 'Request Site Certificate';

    /**
     * The server instance.
     *
     * @var Server
     */
    public $server;

    /**
     * The site instance.
     *
     * @var Site
     */
    public $site;

    /**
     * Allowed server types the playbook can run on.
     *
     * @return void
     */
    public $serverTypes = [
        'shared', 'dedicated'
    ];

    /**
     * Create a new playbook instance.
     *
     * @param  Site  $restore
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
        $this->server = $site->server;
    }

    /**
     * Get the contents of the playbook.
     *
     * @return string
     */
    public function playbook()
    {
        return 'ansible/playbooks/site/cert-request.yml';
    }

    /**
     * Get the variables for the playbook.
     *
     * @return array
     */
    public function vars()
    {
        $domains = [];
        foreach ($this->site->domains as $domain) {
            $domains[] = $domain->name;
        }

        return array_merge(parent::vars(), [
            'user' => (string) $this->site->sysuser->name,
            'site' => (string) $this->site->name,
            'domain' => (string) $this->site->domain,
            'domains' => (array) $domains,
            'email' => (string) 'letsencrypt@' . $this->site->domain
        ]);
    }

    /**
     * Get the timeout for the playbook.
     *
     * @return int|null
     */
    public function timeout()
    {
        return 300;
    }
}
