misEngine.class('component.widget.wards', function() {
    return {
        config: {
            name: 'component.widget.wards',
            id: null
        },
        popover : null,
        bedsPopover : null,
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
                    this.hidePopover();
                }
                this.initPopover($(e.target));
                e.stopPropagation();
            }, this));

            $(document).on('click', '.wardsSettings .wardsList li:not(.new)', $.proxy(function(e) {
                if(this.bedsPopover) {
                    this.hideBedsPopover();
                }
                this.initBedsPopover($(e.target));
                e.stopPropagation();
            }, this));

            $(document).on('click', '#addNewWard', function(e) {
                $(this).prop({
                   'disabled' : true
                });

                var popover =  $(this).popover({
                   animation: true,
                   html: true,
                   placement: 'auto',
                   title: 'Добавить палату',
                   delay: {
                       show: 300,
                       hide: 300
                   },
                   template : $('<div>').prop({
                       'class' : 'popover popover-wardadd',
                       'role' : 'tooltip'
                   }).append($('<div>').addClass('arrow'), $('<h3>').addClass('popover-title'), $('<div>').addClass('popover-content')),
                   container: $(this).parents('li'),
                   content: $.proxy(function () {
                       return $('.settingsFormCont').html();
                   }, this)
                });

                $(this).popover('show');

                var span = $('<span class="glyphicon glyphicon-remove" title="Закрыть окно">').css({
                    position: 'absolute',
                    cursor: 'pointer',
                    left: '480px'
                });

                $(span).on('click', $.proxy(function(e) {
                    $(this).popover('destroy');
                    $(this).prop({
                        'disabled' : false
                    });
                    e.stopPropagation();
                    return false;
                }, this));

                $(this).parents('li').find('.popover').append(span).on('click', function() {
                    return false;
                })
            });

            $(document).on('click', '.resetFilter', $.proxy(function() {
                $('#notPaidWard,  #paidWard, #paidBeds, #notPaidBeds').removeAttr('checked');
                $('#wardType').val(-1);
                this.updateList();
            }, this));
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

        initPopover : function(li) {
            this.popover = $(li).popover({
                animation: true,
                html: true,
                placement: 'bottom',
                title: 'Настройки',
                delay: {
                    show: 300,
                    hide: 300
                },
                template : $('<div>').prop({
                    'class' : 'popover popover-wardedit',
                    'role' : 'tooltip'
                }).append($('<div>').addClass('arrow'), $('<h3>').addClass('popover-title'), $('<div>').addClass('popover-content')),
                container: $(li).parents('li'),
                content: $.proxy(function () {
                    return $('.settingsFormCont').html();
                }, this)
            });
            $(li).popover('show');

            var span = $('<span class="glyphicon glyphicon-remove" title="Закрыть окно">').css({
                position: 'absolute',
                cursor: 'pointer',
                left: '480px'
            });

            $(span).on('click', function(e) {
                $(li).popover('destroy');
                e.stopPropagation();
                return false;
            });

            $(li).parents('li').find('.popover').append(span).on('click', function() {
                return false;
            })
        },

        hidePopover : function() {
            this.popover.popover('destroy');
            this.popover = null;
        },

        initBedsPopover : function(li) {
            this.bedsPopover = $(li).popover({
                animation: true,
                html: true,
                placement: 'bottom',
                title: 'Настройка коек',
                delay: {
                    show: 300,
                    hide: 300
                },
                template : $('<div>').prop({
                    'class' : 'popover popover-bedsedit',
                    'role' : 'tooltip'
                }).append($('<div>').addClass('arrow'), $('<h3>').addClass('popover-title'), $('<div>').addClass('popover-content')),
                container: $(li),
                content: $.proxy(function () {
                    return $('.bedsEditCont').html();
                }, this)
            });
            $(li).popover('show');

            var span = $('<span class="glyphicon glyphicon-remove" title="Закрыть окно">').css({
                position: 'absolute',
                cursor: 'pointer',
                left: '480px'
            });

            $(span).on('click', function(e) {
                $(li).popover('destroy');
                e.stopPropagation();
                return false;
            });

            $(li).parents('li').find('.popover').append(span).on('click', function() {
                return false;
            })
        },

        hideBedsPopover : function() {
            this.bedsPopover.popover('destroy');
            this.bedsPopover = null;
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