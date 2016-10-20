<?php

class_exists('CApi') or die();

class COverrideDefaultDomainSettingsPlugin extends AApiPlugin
{
	/**
	 * @param CApiPluginManager $oPluginManager
	 */
	public function __construct(CApiPluginManager $oPluginManager)
	{
		parent::__construct('1.0', $oPluginManager);

		$this->AddHook('api-pre-create-account-process-call', 'PluginApiPreCreateAccountProcessCall');
	}

	/**
	 * @param CAccount $oAccountToCreate
	 */
	public function PluginApiPreCreateAccountProcessCall(&$oAccountToCreate)
	{
		if ($oAccountToCreate instanceof CAccount && $oAccountToCreate->Domain && $oAccountToCreate->Domain->IsDefaultDomain)
		{
			$sEmailDomain = api_Utils::GetDomainFromEmail($oAccountToCreate->Email);
			if (0 < strlen($sEmailDomain))
			{
				$oAccountToCreate->IncomingMailServer = 'mail.'.$sEmailDomain;
				$oAccountToCreate->OutgoingMailServer = 'mail.'.$sEmailDomain;

//				$oAccountToCreate->IncomingMailServer = 'imap.'.$sEmailDomain;
//				$oAccountToCreate->OutgoingMailServer = 'smtp.'.$sEmailDomain;
			}
		}
	}
}

return new COverrideDefaultDomainSettingsPlugin($this);
