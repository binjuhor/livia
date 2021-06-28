<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Invoices;

use Webfox\Xero\OauthCredentialManager;
use XeroAPI\XeroPHP\Api\AccountingApi;
use XeroAPI\XeroPHP\Models\Accounting\Invoice;

trait InteractsWithXeroApi
{
    protected OauthCredentialManager $xeroCredentials;

    protected AccountingApi $xeroAccountingApi;

    public function xeroCredentials(): OauthCredentialManager
    {
        if (is_null($this->xeroCredentials)) {
            $this->xeroCredentials = resolve(OauthCredentialManager::class);
        }

        return $this->xeroCredentials;
    }

    public function xeroAccountingApi(): AccountingApi
    {
        if (is_null($this->xeroCredentials)) {
            $this->xeroAccountingApi = resolve(AccountingApi::class);
        }

        return $this->xeroAccountingApi;
    }

    public function findXeroInvoice(string $xeroId): ?Invoice
    {
        $invoices = $this->xeroAccountingApi()->getInvoice(
            $this->xeroCredentials()->getTenantId(),
            $xeroId
        );

        return $invoices->count() > 0 ?
            collect($invoices->getInvoices())->first()
            : null;
    }
}