Date.dayNames = [
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi"
];
Date.monthNames = [
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
Date.monthNumbers = {
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
Date.shortMonthNames = [
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
Date.getShortMonthName = function(month) {
    return Date.shortMonthNames[month];
};