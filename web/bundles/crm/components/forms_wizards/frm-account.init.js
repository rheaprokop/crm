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
                            //Wizard component make sure that user enters required information.
                            if (!wiz.find('#account_name').val()) {
                                notyfy({text: 'You must fill up Account Title.', type: 'error'});
                                wiz.find('#account_name').focus();
                                return false;
                            }
                            if (!wiz.find('#account_manager').val()) {
                                notyfy({text: 'You must fill up Account Manager.', type: 'error'});
                                wiz.find('#account_manager').focus();
                                return false;
                            }

                            if (!wiz.find('#account_shortdesc').val()) {
                                notyfy({text: 'You must fill up Short Description.', type: 'error'});
                                wiz.find('#account_shortdesc').focus();
                                return false;
                            }

                            if (!wiz.find('#account_primaryname').val()) {
                                notyfy({text: 'You must provide your primary contact\'s name.', type: 'error'});
                                wiz.find('#account_primaryname').focus();
                                return false;
                            }

                            if (!wiz.find('#account_primaryemail').val()) {
                                notyfy({text: 'You must provide your primary contact\'s email.', type: 'error'});
                                wiz.find('#account_primaryemail').focus();
                                return false;
                            }

                            if (!wiz.find('#account_primaryphone').val()) {
                                notyfy({text: 'You must provide your primary contact\'s phone.', type: 'error'});
                                wiz.find('#account_primaryphone').focus();
                                return false;
                            }
 
                        }

                        if (index == 2)
                        {
                            //Wizard component make sure that user enters required information.
                            if (!wiz.find('#account_name').val()) {
                                notyfy({text: 'You must fill up Account Title.', type: 'error'});
                                wiz.find('#account_name').focus();
                                return false;
                            }
                        }
                    },
                    onLast: function(tab, navigation, index)
                    {
                        // Make sure we entered the title


                        if (index == 2)
                        {
                            // Make sure we entered the title

                            //Wizard component make sure that user enters required information.
                            if (!wiz.find('#account_name').val()) {
                                notyfy({text: 'You must fill up Account Title.', type: 'error'});
                                wiz.find('#account_name').focus();
                                return false;
                            }
                        }
                    },
                    onTabClick: function(tab, navigation, index)
                    {
                        //Wizard component make sure that user enters required information.
                        if (!wiz.find('#account_name').val()) {
                            notyfy({text: 'You must fill up Account Title.', type: 'error'});
                            wiz.find('#account_name').focus();
                            return false;
                        }


                        // Make sure we entered a contact number

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
            wiz.find("a[data-toggle*='tab']:first").trigger('click');
        });
    });
});