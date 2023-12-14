/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */

"use strict";

// begin for inputPrognosaAr and inputPrognosaKasi
$("[data-checkboxes]").each(function () {
    var me = $(this),
        group = me.data('checkboxes'),
        role = me.data('checkbox-role');

    me.change(function () {
        var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
            checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
            dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
            total = all.length,
            checked_length = checked.length;

        if (role == 'dad') {
            if (me.is(':checked')) {
                all.prop('checked', true);
            } else {
                all.prop('checked', false);
            }
        } else {
            if (checked_length >= total) {
                dad.prop('checked', true);
            } else {
                dad.prop('checked', false);
            }
        }
    });
});

class GetDate {
    #dateNow;
    constructor(dateFromLocal = null) {
        this.#dateNow = dateFromLocal ? new Date(dateFromLocal) : new Date();
    }

    getDateNowFormatted() {
        return `${this.#dateNow.getFullYear()}-${this.#dateNow.getMonth() + 1}-${this.#dateNow.getDate()}`;
    }

    getDateLastDay(m = this.#dateNow.getMonth(), y = this.#dateNow.getFullYear()) {
        const lastDay = new Date(y, m + 1, 0).getDate();
        return `${y}-${m + 1}-${lastDay}`;
    }
}

// if (jQuery().daterangepicker) {
//     if ($(".date-picker").length) {
//         const myDate = new GetDate();
//         $('.date-picker').daterangepicker({
//             locale: { format: 'YYYY-MM-DD' },
//             singleDatePicker: true,
//             "minDate": myDate.getDateNowFormatted(),
//             "maxDate": myDate.getDateLastDay()
//         })
//     }
// }

$(".currency").toArray().forEach(element => {
    new Cleave(element, {
        numeral: true,
        numeralThousandsGroupStyle: "thousand"
    });
});

// end for inputPrognosaAr and inputPrognosaKasi