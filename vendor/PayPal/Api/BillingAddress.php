<?php

namespace PayPal\Api;

/**
 * Class BillingAddress
 *
 * Extended Address object used as billing address in a payment.
 *
 * @package PayPal\Api
 *
 * @property string recipient_name
 */
class BillingAddress extends Address
{
    /**
     * Address ID assigned in PayPal system.
     * @deprecated Not publicly available
     * @param string $id
     * 
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Address ID assigned in PayPal system.
     * @deprecated Not publicly available
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Name of the recipient at this address.
     *
     * @param string $recipient_name
     * 
     * @return $this
     */
    public function setRecipientName($recipient_name)
    {
        $this->recipient_name = $recipient_name;
        return $this;
    }

    /**
     * Name of the recipient at this address.
     *
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipient_name;
    }

    /**
     * Default shipping address of the Payer.
     * @deprecated Not publicly available
     * @param bool $default_address
     * 
     * @return $this
     */
    public function setDefaultAddress($default_address)
    {
        $this->default_address = $default_address;
        return $this;
    }

    /**
     * Default shipping address of the Payer.
     * @deprecated Not publicly available
     * @return bool
     */
    public function getDefaultAddress()
    {
        return $this->default_address;
    }

    /**
     * Shipping Address marked as preferred by Payer.
     * @deprecated Not publicly available
     * @param bool $preferred_address
     * 
     * @return $this
     */
    public function setPreferredAddress($preferred_address)
    {
        $this->preferred_address = $preferred_address;
        return $this;
    }

    /**
     * Shipping Address marked as preferred by Payer.
     * @deprecated Not publicly available
     * @return bool
     */
    public function getPreferredAddress()
    {
        return $this->preferred_address;
    }

    /**
     * The invoice recipient email address. Maximum length is 260 characters.
     *
     * @param string $email
     * 
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * The invoice recipient email address. Maximum length is 260 characters.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * The invoice recipient first name. Maximum length is 30 characters.
     *
     * @param string $first_name
     * 
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * The invoice recipient first name. Maximum length is 30 characters.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * The invoice recipient last name. Maximum length is 30 characters.
     *
     * @param string $last_name
     * 
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * The invoice recipient last name. Maximum length is 30 characters.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * The invoice recipient company business name. Maximum length is 100 characters.
     *
     * @param string $business_name
     * 
     * @return $this
     */
    public function setBusinessName($business_name)
    {
        $this->business_name = $business_name;
        return $this;
    }

    /**
     * The invoice recipient company business name. Maximum length is 100 characters.
     *
     * @return string
     */
    public function getBusinessName()
    {
        return $this->business_name;
    }

    /**
     * The invoice recipient address.
     *
     * @param \PayPal\Api\InvoiceAddress $address
     * 
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * The invoice recipient address.
     *
     * @return \PayPal\Api\InvoiceAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * The language in which the email was sent to the payer. Used only when the payer does not have a PayPal account.
     * Valid Values: ["da_DK", "de_DE", "en_AU", "en_GB", "en_US", "es_ES", "es_XC", "fr_CA", "fr_FR", "fr_XC", "he_IL", "id_ID", "it_IT", "ja_JP", "nl_NL", "no_NO", "pl_PL", "pt_BR", "pt_PT", "ru_RU", "sv_SE", "th_TH", "tr_TR", "zh_CN", "zh_HK", "zh_TW", "zh_XC"]
     *
     * @param string $language
     * 
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * The language in which the email was sent to the payer. Used only when the payer does not have a PayPal account.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Additional information, such as business hours. Maximum length is 40 characters.
     *
     * @param string $additional_info
     * 
     * @return $this
     */
    public function setAdditionalInfo($additional_info)
    {
        $this->additional_info = $additional_info;
        return $this;
    }

    /**
     * Additional information, such as business hours. Maximum length is 40 characters.
     *
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additional_info;
    }

}
