misEngine.class('component.widget.wards', function() {
    return {
        config: {
            name: 'component.widget.wards',
            id: null
        },
        popover : null,
        bedsPopover : null,
        bedEditPopover : null,
        bedAddPopover : null,
        wardAddPopover : null,
        tabmarks : [],

        displayTabmarks : function() {
            this.tabmarks = [
                misEngine.create('component.tabmark', {
                    selector : '#allWardsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'ComissionGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#allWardsTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline');
                                    } else {
                                        $('#allWardsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                }),
                misEngine.create('component.tabmark', {
                    selector : '#paidWardsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'QueueGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#paidWardsTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline')
                                    } else {
                                        $('#paidWardsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                }),
                misEngine.create('component.tabmark', {
                    selector : '#notPaidWardsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'ComissionGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#notPaidWardsTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline');
                                    } else {
                                        $('#notPaidWardsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                }),
                misEngine.create('component.tabmark', {
                    selector : '#paidBedsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'HospitalizationGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#paidBedsTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline');
                                    } else {
                                        $('#paidBedsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                }),
                misEngine.create('component.tabmark', {
                    selector : '#notPaidBedsTabmark',
                    renderConfig : {
                        mode : 'ajax',
                        ajaxConf : {
                            url : '/hospital/components/tabmark',
                            data : {
                                serverModel : 'HistoryGrid'
                            },
                            type : 'GET',
                            dataType : 'json',
                            success : function(data, status, jqXHR) {
                                if(data.success) {
                                    if(data.num > 0) {
                                        $('#historyTabmark .roundedLabelText')
                                            .text(data.num)
                                            .parent()
                                            .css('display', 'inline');
                                    } else {
                                        $('#notPaidBedsTabmark').hide();
                                    }
                                }
                            },
                            error: function(jqXHR, status, errorThrown) {
                                misEngine.t(jqXHR, status, errorThrown);
                            }
                        }
                    }
                })
            ];

            $(this.tabmarks).each(function(index, element) {
                element.updateTabmark();
            });
        },

        bindHandlers : function() {
            $(document).on('click', '.wardsSettings .wardsList .settings', $.proxy(function(e) {
                if(this.popover) {
                    this.hideWardSettingsPopover();
                }
                this.initWardSettingsPopover($(e.target));
                e.stopPropagation();
            }, this));

            $(document).on('click', '.wardsSettings .wardsList li:not(.new)', $.proxy(function(e) {
                if(this.bedsPopover) {
                    this.hideBedsPopover();
                }
                this.initBedsPopover($(e.target));
                e.stopPropagation();
            }, this));

            $(document).on('dblclick', '.bed-settings', $.proxy(function(e) {
                if(this.bedEditPopover) {
                    this.hideBedPopover();
                }
                this.initBedPopover($(e.target), parseInt($(e.target).prop('id').substr(1)));
                e.stopPropagation();
            }, this));

            $(document).on('dblclick', '.bed-add', $.proxy(function(e) {
                if(this.bedAddPopover) {
                    this.hideBedPopover();
                }
                this.initBedPopover($(e.target), parseInt($(e.target).prop('id').substr(1)));
                e.stopPropagation();
            }, this));

            $(document).on('mouseover', '.settings, .bed-settings', function(e) {
                $(e.target).addClass('cog-blinking-css');
            });

            $(document).on('mouseout', '.settings .popover, .bed-settings .popover', function(e) {
                e.stopPropagation();
            });

            $(document).on('mouseover', '.settings .popover, .bed-settings .popover', function(e) {
                e.stopPropagation();
            });

            $(document).on('mouseout', '.settings, .bed-settings', function(e) {
                $(e.target).removeClass('cog-blinking-css');
            });

            $(document).on('click', '#addNewWard', $.proxy(function(e) {
                $(e.target).prop({
                   'disabled' : true
                });

                this.initPopover({
                    prop : 'wardAddPopover',
                    selector : $(e.target),
                    placement : 'auto',
                    title : 'Добавить палату',
                    templateClass : 'popover-wardadd',
                    content : $('.settingsFormCont').html(),
                    container : $(e.target).parent(),
                    crossLeft : '580px'
                });

                var _e = e;

                $(document).on('click', '.popover-wardadd .popover-title span', function(e) {
                    $(_e.target).prop({
                        'disabled' : false
                    });
                    e.stopPropagation();
                    return false;
                });
            }, this));

            $(document).on('click', '.resetFilter', $.proxy(function() {
                $('#notPaidWard,  #paidWard, #paidBeds, #notPaidBeds').removeAttr('checked');
                $('#wardType').val(-1);
                this.updateList();
            }, this));


            $(document).on('click', '.bed-add', function(e) {

            });
        },

        updateList: function() {
            $('.wardsList').parent().css({
                'position' : 'relative'
            }).prepend(
                $('<div>').addClass('overlay').css('top', '-5px')
            );

            // Here must be ajax for update wardsList, TODO
            $('.overlay').remove();
        },

        initWardSettingsPopover : function(li) {
            this.initPopover({
                prop : 'popover',
                selector : li,
                placement : 'bottom',
                title : 'Настройки',
                templateClass : 'popover-wardedit',
                content : $('.settingsFormCont').html(),
                container : li,
                crossLeft : '580px'
            });
        },

        hideWardSettingsPopover : function() {
            this.popover.popover('destroy');
            this.popover = null;
        },

        initBedsPopover : function(li) {
            this.initPopover({
                prop : 'bedsPopover',
                selector : li,
                placement : 'bottom',
                title : 'Настройка коек',
                templateClass : 'popover-bedsedit',
                content : $('.bedsEditCont').html(),
                container : li,
                crossLeft : '580px'
            });
        },

        hideBedsPopover : function() {
            this.bedsPopover.popover('destroy');
            this.bedsPopover = null;
            if(this.bedEditPopover) {
                this.hideBedPopover();
            }
        },

        initBedPopover : function(contSpan, bedId) {
            this.initPopover({
                prop : 'bedEditPopover',
                selector : contSpan,
                placement : 'bottom',
                title : 'Настройка койки',
                templateClass : 'popover-bededit',
                content : $('.bedSettingsFormCont').html(),
                container : $(contSpan).parent(),
                crossLeft : '580px'
            });
        },

        hideBedPopover : function() {
            this.bedEditPopover.popover('destroy');
            this.bedEditPopover = null;
        },


        initPopover : function(config) {
            this[config.prop] = $(config.selector).popover({
                animation: true,
                html: true,
                placement: config.placement,
                title: config.title,
                delay: {
                    show: 300,
                    hide: 300
                },
                template : $('<div>').prop({
                    'class' : 'popover ' + config.templateClass,
                    'role' : 'tooltip'
                }).append($('<div>').addClass('arrow'), $('<h3>').addClass('popover-title'), $('<div>').addClass('popover-content')),
                container: $(config.container),
                content: $.proxy(function () {
                    return config.content;
                }, this)
            });

            $(config.selector).popover('show');

            var span = $('<span class="glyphicon glyphicon-remove" title="Закрыть окно">').css({
                position: 'absolute',
                cursor: 'pointer',
                left: config.crossLeft
            });

            $(span).on('click', function(e) {
                $(config.selector).popover('destroy');
                e.stopPropagation();
                return false;
            });

            $(config.selector).parent().find('.popover').append(span).on('click', function() {
                return false;
            })
        },

        run : function() {
            this.bindHandlers();
            this.displayTabmarks();
            return this;
        },

        init : function(config) {
            if(this.config) {
                this.setConfig(config);
            }
            return this;
        }
    }
});