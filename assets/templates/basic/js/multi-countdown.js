'use strict';

$(function () {

    // CONFIG

    let mainClass = '.countdown';
    let OffsetLocation = -4;

    let runningClass = '.running'; //optinonal
    let endedClass = ".ended"; //optional

    // END CONFIG


    //init
    let date, fixTime, index = 0, extraClass, initText, zeroPad;
    $(mainClass).each(function () { //for each countdown instance
        index++;
        date = $(this).attr('data-Date');
        fixTime = $(this).attr('data-fixTime');
        zeroPad = $(this).attr('data-zeroPad');
        extraClass = 'd_' + index;

        $(this).addClass(extraClass); //add a class to recognize each counter
        $(this).css('display','block'); //allow to start hidding the class to avoid a bad effect until js is loading

        if (fixTime != undefined) date = getFixDate(fixTime);

        //get init text with or whitout an extra Class
        if ($('.' + extraClass + ' ' + runningClass + ' timer').length) {
            initText = $('.' + extraClass + ' ' + runningClass + ' timer').text();
        } else {
            initText = $(this).text();
        }
        //show and hide classes
        $('.' + extraClass + ' ' + runningClass).css('display', 'flex');
        $('.' + extraClass + ' ' + endedClass).css('display', 'none');

        //call main function
        dateReplace(extraClass, date, fixTime, initText, zeroPad); //prevent delay for the first time
        setInterval(dateReplace, 1000, extraClass, date, fixTime, initText, zeroPad);
    });

    function dateReplace(extraClass, date, fixTime, initText, zeroPad) {
        let dif = timeDistance(date, fixTime);
        let text = initText;
        let zeroPadArr = [];
        if (dif[0] < 0 || dif[1] < 0 || dif[2] < 0 || dif[3] < 0) {
            //countdown ended
            let endText = $('.' + extraClass).attr('data-endText');
            if (endText != undefined) { //case data-endText attr
                $('.' + extraClass).text(endText);
            } else { //case with two blocks
                $('.' + extraClass + ' ' + runningClass).css('display', 'none');
                $('.' + extraClass + ' ' + endedClass).css('display', 'flex');
            }

        } else {

            //Zero-pad
           if(zeroPad != undefined) zeroPadArr = JSON.parse(zeroPad);

            if (zeroPadArr['Days'] != "false") dif[0] = String(dif[0]).padStart(2, '0');
            if (zeroPadArr['Hours'] != "false") dif[1] = String(dif[1]).padStart(2, '0');
            if (zeroPadArr['Minutes'] != "false") dif[2] = String(dif[2]).padStart(2, '0');
            if (zeroPadArr['Seconds'] != "false") dif[3] = String(dif[3]).padStart(2, '0');

            //replace text with or without extra class

            //whith extras Class
            if ($('.' + extraClass + ' ' + runningClass + ' timer').length) {
                $('.' + extraClass + ' ' + runningClass + ' timer .days').text(dif[0]);
                $('.' + extraClass + ' ' + runningClass + ' timer .hours').text(dif[1]);
                $('.' + extraClass + ' ' + runningClass + ' timer .minutes').text(dif[2]);
                $('.' + extraClass + ' ' + runningClass + ' timer .seconds').text(dif[3]);

            } else {

                //replace parts without extra Class
                text = text.replace('(days)', dif[0]);
                text = text.replace('(hours)', dif[1]);
                text = text.replace('(minutes)', dif[2]);
                text = text.replace('(seconds)', dif[3]);
                $('.' + extraClass).text(text);
            }
            pluralization(extraClass, dif);

        }
    }

    function timeDistance(date, fixTime) {
        var date1 = new Date(date);
        let date2, d, utc;

        d = new Date();
        utc = d.getTime() + (d.getTimezoneOffset() * 60000);
        if (fixTime != undefined) date2 = new Date();
        else date2 = new Date();

        var diff = date1.getTime() - date2;
        var msec = diff;
        var hh = Math.floor(msec / 1000 / 60 / 60);
        msec -= hh * 1000 * 60 * 60;
        var mm = Math.floor(msec / 1000 / 60);
        msec -= mm * 1000 * 60;
        var ss = Math.floor(msec / 1000);
        msec -= ss * 1000;
        var dd = Math.floor(hh / 24);
        if (dd > 0) {
            hh = hh - (dd * 24);
        }
        return [dd, hh, mm, ss];
    }

    function getFixDate(fixTime) {
        let getFixTimeDate = 0;

        var fixTimeDate = JSON.parse($('.' + extraClass).attr('data-fixTime'));
        if (fixTimeDate['Days'] != undefined) { getFixTimeDate += +fixTimeDate['Days'] * 60 * 24; }
        if (fixTimeDate['Hours'] != undefined) { getFixTimeDate += +fixTimeDate['Hours'] * 60; }
        if (fixTimeDate['Minutes'] != undefined) getFixTimeDate += +fixTimeDate['Minutes'];

        var now = new Date();
        now.setMinutes(now.getMinutes() + getFixTimeDate); // timestamp
        date = new Date(now); // Date object

        return date;
    }

    // Note this *is* JQuery, see below for JS solution instead
    function replaceText(selector, text, newText, flags) {
        var matcher = new RegExp(text, flags);
        $(selector).each(function () {
            var $this = $(this);
            if (!$this.children().length)
                $this.text($this.text().replace(matcher, newText));
        });
    }

    function pluralization(extraClass, dif) {
        //pluralization
        if (dif[0] == 1) replaceText('.' + extraClass, 'p_days', 'Day', 'g');
        else replaceText('.' + extraClass, 'p_days', 'Days', 'g');

        if (dif[1] == 1) replaceText('.' + extraClass, 'p_hours', 'Hour', 'g');
        else replaceText('.' + extraClass, 'p_hours', 'Hours', 'g');

        if (dif[2] == 1) replaceText('.' + extraClass, 'p_minutes', 'Minute', 'g');
        else replaceText('.' + extraClass, 'p_minutes', 'Minutes', 'g');

        if (dif[3] == 1) replaceText('.' + extraClass, 'p_seconds', 'Second', 'g');
        else replaceText('.' + extraClass, 'p_seconds', 'Seconds', 'g');
    }

})