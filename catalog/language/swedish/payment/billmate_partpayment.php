<?php
// Text
$_['text_title']           = 'Billmate delbetalning';
$_['text_pay_month']  = 
$_['text_no_fee']     = '<img src="billmate/images/bm_delbetalning_l.png" alt="Billmate delbetalning" style="float: left;margin: 2px 6px;">Billmate delbetalning - Betala från %s/månad <a id="terms-delbetalning">Köpvillkor</a><script type="text/javascript">$.getScript("https://efinance.se/billmate/base.js", function(){
		$("#terms").Terms("villkor",{invoicefee:29});
		$("#terms-delbetalning").Terms("villkor_delbetalning",{eid: %s,effectiverate:34});
});</script>';
$_['text_information']     = 'Billmate delbetalning information';
$_['text_additional']      = 'Billmate delbetalning behöver lite mer information för att processa din order.';
$_['text_wait']            = 'Var god vänta!';
$_['text_male']            = 'Man';
$_['text_female']          = 'Kvinna';
$_['text_year']            = 'År';
$_['text_month']           = 'Månad';
$_['text_day']             = 'Dag';
$_['text_payment_option']  = 'Betalningsalternativ';
$_['text_single_payment']  = 'En betalning';
$_['text_monthly_payment'] = '%s - %s per månad';
$_['text_comment']         = "Billmates fakturanummer ID: %s";
$_['your_billing_wrong']  = "Köp mot faktura kan bara göras till den adress som är angiven i folkbokföringen. Vill du genomföra köpet med adressen:";
$_['correct_address_is']  = 'Din bokföringsadress: ';
$_['if_u_continue']       = 'Klicka Byt för att byta faktura och leveransadress. Klicka Avbryt för att välja en annan betalningsmetod.';
$_['bill_yes']            = 'Ja, genomför köp med denna adress';
$_['bill_no']             = 'Nej jag vill ange ett annat personnummer eller byta betalsätt';
$_['else_click']          = 'eller klicka';
$_['requried_pno'] 	      = 'Ej giltigt organisations-/personnummer. Kontrollera numret.';
$_['wrong_person_number'] = "Ej giltigt organisations-/personnummer. Kontrollera numret.";

// Entry
$_['Close'] 			  = 'Stäng';
$_['entry_gender']         = 'Kön:';
$_['entry_pno']            = 'Organisations-/personnummer:<br /><span class="help">Vänligen skriv in ditt organisationsnummer (företag) eller personnummer (privat).</span>';
$_['entry_dob']            = 'Date of Birth:';
$_['entry_phone_no']       = 'Min e-postadress %s är korrekt och får användas för fakturering.';//Mobilnummer:<br /><span class="help">Vänligen skriv in ditt mobilnummer.</span>';
$_['entry_street']         = 'Adress:<br /><span class="help">Vänligen notera att leverans och fakturaadress måste vara samma som fokbokföringsadress när ni betalar med Billmate.</span>';
$_['entry_house_no']       = 'House No.:<br /><span class="help">Please enter your house number.</span>';
$_['entry_house_ext']      = 'House Ext.:<br /><span class="help">Please submit your house extension here. E.g. A, B, C, Red, Blue ect.</span>';
$_['entry_company']        = 'Organisationsnummer:<br /><span class="help">Vänligen skriv in ert företags organisationsnummer.</span>';

// Error
$_['error_deu_terms']      = 'You must agree to Billmate\'s privacy policy';
$_['error_address_match'] = 'Faktura och leveransadress måste vara samma om du ska använda billmate faktura.';
$_['error_network']       = 'Ingen kontakt med Billmate server. Försök igen senare.';
$_['required_pno']        = 'Personnummer krävs.';
$_['requried_confirm_verify_email']='Vänligen kryssa i rutan för att bekräfta att er e-postadress är giltig.';
$_['close'] = 'Stäng';
