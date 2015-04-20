misEngine.class('component.helper', function() {
	return {
		config : {
			name : 'component.helper',
            selector : null,
            iconContainer : null,
            file : null
        },
        tooltips : [],
        displayed : 0,
        learnList : [],
		modePopover : null,
        stepTooltipsList : [],

		bindHandlers : function() {
            $(document).on('click', '#simpleMode', $.proxy(function(e) {
                this.hideModePopover();
                this.hideStep();
                this.displayTooltips(this.tooltips);
            }, this));
		},

        makeIcon : function() {
            if(this.config.iconContainer) {
                $(this.config.iconContainer).append(
                    $('<a>').prop({
                        'href' : '#'
                    }).append(
                        $('<img>').prop({
                            'width' : 64,
                            'height' : 64,
                            'alt' : 'Помощь',
                            'title' : 'Помощь',
                            'src' : '/images/icons/help_7103.png'
                        })
                    ).on('click', $.proxy(function(e) {
                        if(!this.displayed) {
                            if(this.learnList) {
                                // If isset learn list, user must to choose, what he wants
                                this.displayModePopover();
                            } else {
                                this.displayTooltips(this.tooltips);
                            }
                        } else {
                            this.hideTooltips();
                        }
                        return false;
                    }, this))
                )
            }
        },

        displayModePopover : function() {
            this.modePopover = $(this.config.iconContainer).popover({
                animation: true,
                html: true,
                placement: 'auto',
                title: 'Помощь',
                delay: {
                    show: 300,
                    hide: 300
                },
                template : $('<div>').prop({
                    'class' : 'popover popover-helpsystem-mode',
                    'role' : 'tooltip'
                }).append(
                    $('<div>').addClass('arrow'),
                    $('<h5>').addClass('popover-title'),
                    $('<div>').addClass('popover-content')
                ),
                container: $(this.config.iconContainer),
                content: $.proxy(function () {
                    return  $('<div>').append(
                        $('<a>').prop({
                            'href' : '#',
                            'id' : 'simpleMode'
                        }).text('Подсказки'),
                        $('<h6>').text('Режим обучения'),
                        $('<ul>').prop({
                            'id' : 'learnMode'
                        }).append(this.getLearnHeaders())
                    );
                }, this)
            });

            $(this.config.iconContainer).popover('show')
        },

        hideModePopover : function() {
            if(this.modePopover) {
                this.modePopover.popover('destroy');
                this.modePopover = null;
            }
        },

        getLearnHeaders : function() {
            return this.learnList.map(function(item) {
               return item.li;
            });
        },

        makeTooltips : function() {
            if(this.config.file) {
                $.getScript(this.config.file, $.proxy(function(data, textStatus, jqXHR) {
                    data = eval(data);
                    for(var i = 0; i < data.length; i++) {
                        if(data[i].groupName) {
                            var li = $('<li>').html(
                                $('<a>')
                                    .on('click', $.proxy(this.displayLearnMode, this))
                                    .prop({
                                        'href': '#',
                                        'id' : 'i' + i
                                    })
                                    .text(data[i].groupName)
                            );
                            if(data[i].icon) {
                                li.css({
                                    'listStyleImage' : 'url(' + data[i].icon + ')'
                                }).find('a').css({
                                    'position' : 'relative',
                                    'top' : '-8px'
                                });
                            }
                            this.learnList.push({
                                li : li,
                                index : i // This is link to tooltips list
                            });
                        }

                        this.tooltips.push(data[i]);
                    }
                }, this));
            }
        },

        displayLearnMode : function(e) {
            var groupIndex = parseInt($(e.target).prop('id').substr(1));
            if(!groupIndex) {
                return false;
            }

            this.displayStep(this.tooltips[groupIndex].tooltips, 0);
            this.hideModePopover();
            e.stopPropagation();
        },

        displayStep : function(tooltips, step) {
            var nextLink = $('<a>').prop({'href' : '#'}).html('Дальше...');
            $(nextLink).on('click', $.proxy(function(e) {
                this.hideStep();
                this.displayStep(tooltips, this.getNextStep(tooltips, step));
                e.stopPropagation();
            }, this));
            this.displayTooltips(this.getLearnStep(tooltips, step), nextLink);
        },

        hideStep : function() {
            for(var i = 0; i < this.stepTooltipsList.length; i++) {
                this.stepTooltipsList[i].popover('destroy');
            }
            this.stepTooltipsList = [];
        },

        getNextStep : function(tooltips, currentStep) {
            var nextStep = currentStep;
            for(var i = 0; i < tooltips.length; i++) {
                if((tooltips[i].step > nextStep && nextStep == currentStep) || (tooltips[i].step < nextStep && nextStep != currentStep)) {
                    nextStep = tooltips[i].step;
                }
            }
            if(nextStep == currentStep) {
                return 0; // First step: no next steps in tooltips
            }
            return nextStep;
        },

        getLearnStep : function(tooltipsArr, step) {
            return tooltipsArr.filter(function(item) {
                return item.step == step;
            });
        },

        displayTooltips : function(tooltips, footerContent) {
            for(var i = 0; i < tooltips.length; i++) {
                var popover = $(tooltips[i].selector).popover({
                    animation: true,
                    html: true,
                    placement: tooltips[i].placement ? tooltips[i].placement : 'auto',
                    title: tooltips[i].header,
                    delay: {
                        show: 300,
                        hide: 300
                    },
                    template : $('<div>').prop({
                        'class' : 'popover popover-helpsystem',
                        'role' : 'tooltip'
                    }).append(
                        $('<div>').addClass('arrow'),
                        $('<h5>').addClass('popover-title'),
                        $('<div>').addClass('popover-content'),
                        footerContent ? $('<div>').addClass('popover-footer').append(footerContent) : ''),
                    container: $(tooltips[i].selector),
                    content: $.proxy(function () {
                        return $(tooltips[i].body);
                    }, this)
                });

                if(typeof tooltips[i].step != 'undefined') { // This is learn-mode tooltips..
                    this.stepTooltipsList.push(popover);
                }
                $(tooltips[i].selector).popover('show');
            }
            this.displayed = 1;
        },

        hideTooltips : function() {
            for(var i = 0; i < this.tooltips.length; i++) {
                $(this.tooltips[i].selector).popover('destroy');
            }
            this.displayed = 0;
        },

		init : function(config) {
            if(config) {
                this.setConfig(config);
            }

            this.makeIcon();
            this.makeTooltips();
            this.bindHandlers();
			return this;
		}
	};
});