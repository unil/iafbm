Ext.Date.dayNames = [
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi"
];
Ext.Date.monthNames = [
    "janvier",
    "février",
    "mars",
    "avril",
    "mai",
    "juin",
    "juillet",
    "août",
    "septembre",
    "octobre",
    "novembre",
    "décembre"
];
Ext.Date.monthNumbers = {
    'jan':0,
    'fév':1,
    'mar':2,
    'avr':3,
    'mai':4,
    'jui':5,
    'juil':6,
    'aou':7,
    'sep':8,
    'oct':9,
    'nov':10,
    'dec':11
};
// This is a custom array for overriden Date.getShortMonthName()
Ext.Date.shortMonthNames = [
    'jan',
    'fév',
    'mar',
    'avr',
    'mai',
    'jui',
    'juil',
    'aou',
    'sep',
    'oct',
    'nov',
    'dec'
];
Ext.Date.getShortMonthName = function(month) {
    return Ext.Date.shortMonthNames[month];
};
Ext.Date.defaultFormat = 'd m Y';


Ext.define('Ext.ia.form.field.Date', {
    extend:'Ext.form.field.Date',
    alias: 'widget.ia-datefield',
    requires: ['Ext.picker.Date'],
    format: 'd.m.Y',
    altFormats: 'd.m.Y|d-m-Y|d m Y',
    startDay: 1
});