<?php

/**
 *
 * @category   Ceicom
 * @package   Ceicom_EbitIntegration
 * @author      Jonatan <jonatan@ceicom.com.br>
 * @website    http://www.ceicom.com.br
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Ceicom_EbitIntegration_Block_Adminhtml_Pagamentos extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
  /**
  * Prepare to render
  */
 protected function _prepareToRender()
 {
     $this->addColumn('id_ebit', array(
         'label' => Mage::helper('ceicom_ebitintegration')->__('Pagamentos id e-bit'),
         'style' => 'width:50px',
     ));

     $this->addColumn('pagamentos', array(
         'label' => Mage::helper('ceicom_ebitintegration')->__('Meus Pagamentos'),
         'style' => 'width:100px',
     ));

     $this->_addAfter = false;
     $this->_addButtonLabel = Mage::helper('ceicom_ebitintegration')->__('Adicionar Mais');
 }

 protected function _renderCellTemplate($columnName)
 {
     if (empty($this->_columns[$columnName])) {
         throw new Exception('Wrong column name specified.');
     }

     $payments = Mage::getSingleton('payment/config')->getActiveMethods();
     $methods = array(array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('--Selecione--')));

     foreach ($payments as $paymentCode => $paymentModel) {
          $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
          $methods[$paymentCode] = array(
              'label'   => $paymentTitle,
              'value' => $paymentCode,
          );
      }

      //Pagamentos
      $pagamentosCosts = Mage::getStoreConfig('ceicom_ebitintegration/config/ebitintegration_pagamentos');
      //echo $shippingCosts;

      Mage::log($pagamentosCosts);

      if ($pagamentosCosts) {

          $pagamentosCosts = unserialize($pagamentosCosts);
          if (array_keys($pagamentosCosts)) {
              foreach($pagamentosCosts as $pagamentosCost) {
                if ($paymentCode == $pagamentosCost['pagamentos']) {
                  $namePagamento = $pagamentosCost['pagamentos'];
                }
              }

          } else {
              // errors here
          }
      }

      Mage::log($pagamentosCosts);

     $column     = $this->_columns[$columnName];
     $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
     $value         = $this->getElement()->getValue();

     if($columnName == 'id_ebit')
     {
        $rendered = '<select name="'.$inputName.'">';

        $options = array
        (
          array("value" => "14","label" => "14 (Outros)",),
          array("value" => "05","label" => "05 (Cartão de Crédito)",),
          array("value" => "08","label" => "08 (Boleto Bancário)",),
          array("value" => "24","label" => "24 (Cartão da Loja)",),
          array("value" => "25","label" => "25 (Pagamento por Celular)",),
          array("value" => "28","label" => "28 (Cartão de Débito / Débito em Conta)",)
        );

         foreach($options as $option)
         {
             $selected = $option['value'] == $pagamentosCost['id_ebit'] ?  'selected' : '';
             $rendered .= '<option ' . $selected . ' value="'.$option['value'].'">'.$option['label'].'</option>';
         }

         $rendered .= '</select>';

         return $rendered;
     }
     elseif($columnName == 'pagamentos')
     {

         $rendered = '<select name="'.$inputName.'">';

         foreach($methods as $method)
         {
             $selected = $method['value'] == $pagamentosCost['pagamentos'] ?  'selected' : '';
             $rendered .= '<option ' . $selected . ' value="'.$method['value'].'">'.$method['label'].'</option>';
         }

         $rendered .= '</select>';

         return $rendered;
     }
     else
         return parent::_renderCellTemplate($columnName);

 }
}


 ?>
