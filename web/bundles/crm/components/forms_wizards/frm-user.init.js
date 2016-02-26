/* 
 * This software belongs to Rhea Software S.R.O. 
 * Any other information are specified in the software contract agreement. 
 */


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}

$(function()
{
    var bWizardTabClass = '';
    $('.wizard').each(function()
    {
        if ($(this).is('#rootwizard'))
            bWizardTabClass = 'bwizard-steps';
        else
            bWizardTabClass = '';

        var wiz = $(this);

        $(this).bootstrapWizard(
                {
                    onNext: function(tab, navigation, index)
                    {
                        if (index == 1)
                        {
                            // Make sure we entered the title
                            if (!wiz.find('#user_fullname').val()) {
                                notyfy({text: 'You must fill up fullname field.', type: 'error'});
                                wiz.find('#user_firstname').focus();
                                return false;
                            }
 

                            // Make sure we entered the title
                            if (!wiz.find('#user_email').val()) {
                                notyfy({text: 'You must fill up email field.', type: 'error'});
                                wiz.find('#user_email').focus();
                                return false;
                            }

                            if (!isValidEmailAddress(wiz.find('#user_email').val())) {
                                notyfy({text: 'You must enter valid email ', type: 'error', force: 'true', layout: self.data('layout')});
                                wiz.find('#user_email').focus()
                                return false;
                            }
                        }

                        if (index == 2)
                        {

                            if (wiz.find('#user_password').val() !== wiz.find('#inputPasswordNew2').val()) {
                                notyfy({text: 'Password does not match', type: 'error'});
                                wiz.find('#user_password').focus();
                                return false;
                            }
                        }
                    },
                    onLast: function(tab, navigation, index)
                    {
                        // Make sure we entered the title

                        if (wiz.find('#user_password').val() !== wiz.find('#inputPasswordNew2').val()) {
                            notyfy({text: 'Password does not match', type: 'error'});
                            wiz.find('#user_password').focus();
                            return false;
                        }
                    },
                    onTabClick: function(tab, navigation, index)
                    {
                        // Make sure we entered the title
                        if (!wiz.find('#user_fullname').val()) {
                            notyfy({text: 'You must fill up fullname field.', type: 'error'});
                            wiz.find('#user_firstname').focus();
                            return false;
                        } 

                        // Make sure we entered the title
                        if (!wiz.find('#user_email').val()) {
                            notyfy({text: 'You must fill up email field.', type: 'error'});
                            wiz.find('#user_email').focus();
                            return false;
                        }

                        if (!isValidEmailAddress(wiz.find('#user_email').val())) {
                            notyfy({text: 'You must enter valid email ', type: 'error', force: 'true', layout: self.data('layout')});
                            wiz.find('#user_email').focus()
                            return false;
                        }

                    },
                    onTabShow: function(tab, navigation, index)
                    {
                        var $total = navigation.find('li:not(.status)').length;
                        var $current = index + 1;
                        var $percent = ($current / $total) * 100;

                        if (wiz.find('.progress-bar').length)
                        {
                            wiz.find('.progress-bar').css({width: $percent + '%'});
                            wiz.find('.progress-bar')
                                    .find('.step-current').html($current)
                                    .parent().find('.steps-total').html($total)
                                    .parent().find('.steps-percent').html(Math.round($percent) + "%");
                        }

                        // update status
                        if (wiz.find('.step-current').length)
                            wiz.find('.step-current').html($current);
                        if (wiz.find('.steps-total').length)
                            wiz.find('.steps-total').html($total);
                        if (wiz.find('.steps-complete').length)
                            wiz.find('.steps-complete').html(($current - 1));

                        // mark all previous tabs as complete
                        navigation.find('li:not(.status)').removeClass('primary');
                        navigation.find('li:not(.status):lt(' + ($current - 1) + ')').addClass('primary');

                        // If it's the last tab then hide the last button and show the finish instead
                        if ($current >= $total) {
                            wiz.find('.pagination .next').hide();
                            wiz.find('.pagination .finish').show();
                            wiz.find('.pagination .finish').removeClass('disabled');
                        } else {
                            wiz.find('.pagination .next').show();
                            wiz.find('.pagination .finish').hide();
                        }
                    },
                    tabClass: bWizardTabClass,
                    nextSelector: '.next',
                    previousSelector: '.previous',
                    firstSelector: '.first',
                    lastSelector: '.last'
                });

        wiz.find('.finish').click(function()
        {
            if (wiz.find('#user_password').val() !== wiz.find('#inputPasswordNew2').val()) {
                notyfy({text: 'Password does not match', type: 'error'});
                wiz.find('#user_password').focus();
                return false;
            }
            else
            {
                wiz.find("a[data-toggle*='tab']:first").trigger('click');
            }

        });
    });
});