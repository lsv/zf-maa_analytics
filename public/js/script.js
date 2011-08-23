/* Filter menu */
$('aside#filter').addClass('folded').find('div:first').hide();
$('aside#filter > h3 > a').html('<span>' + $('aside#filter > h3 > a').html().split('').join('</span><span>') + '</span>');
$('aside#filter > h3').click(function() {
    $(this).find('span').toggleClass('ui-icon-circle-minus').parent().parent().toggleClass('folded').find('div:first').toggle();
})

/* Filter click */
$('aside#filter input.venues').click(function () { Uncheckboxes.click('venues') });
$('aside#filter input.sports').click(function () { Uncheckboxes.click('sports') });

