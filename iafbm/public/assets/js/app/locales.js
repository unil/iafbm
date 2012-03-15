/******************************************************************************
 * Additional i18n & locales
**/

/**
 * RowEditor labels
 */
if (Ext.grid.RowEditor) {
    Ext.apply(Ext.grid.RowEditor.prototype, {
        saveBtnText: 'OK',
        cancelBtnText: 'Annuler',
        errorsText: 'Erreurs',
        dirtyText: 'Vous devez enregistrer ou annuler vos modifications'
    });
}

/**
 * MessageBox labels
 */
Ext.window.MessageBox.prototype.buttonText.yes = 'Oui';
Ext.window.MessageBox.prototype.buttonText.no = 'Non';
Ext.window.MessageBox.prototype.buttonText.ok = 'OK';
Ext.window.MessageBox.prototype.buttonText.cancel = 'Annuler';
