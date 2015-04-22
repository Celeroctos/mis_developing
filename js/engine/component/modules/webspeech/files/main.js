misEngine.class('component.webspeech', function() {
    return {
        config : {
            name : 'component.webspeech',
            id : null,
            lang : 'ru',
            continuous : true,
            interimResults : true,
            iconContainer : null
        },
        webSpeechObj : null,
        isRecording : false,
        finalTranscript : '',
        interimTranscript : '',

        bindHandlers : function() {
            this.webSpeechObj.onstart = function(e) {

            };

            this.webSpeechObj.onerror = $.proxy(function(event) {
                if (event.error == 'no-speech') {

                }
                if (event.error == 'audio-capture') {

                }
            }, this);

            this.webSpeechObj.onend = $.proxy(function(e) {

            }, this);

            this.webSpeechObj.onresult = $.proxy(function(e) {
                var interim_transcript = '';
                for (var i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        this.finalTranscript += event.results[i][0].transcript;
                    } else {
                        this.interimTranscript += event.results[i][0].transcript;
                    }
                }
            }, this);
        },

        makeIcon : function() {
            if(this.config.iconContainer) {
                $(this.config.iconContainer).append(
                    $('<a>').prop({
                        'href' : '#'
                    }).on('click', $.proxy(function(e) {
                        if(!this.isRecording) {
                            $(e.target).prop({
                                'src': '/images/icons/microphonehot_4450.png'
                            });
                            this.webSpeechObj.start();
                        } else {
                            $(e.target).prop({
                                'src': '/images/icons/microphonedisabled_8551.png'
                            })
                            this.webSpeechObj.stop();
                        }
                        this.isRecording = !this.isRecording;
                    }, this)).append(
                        $('<img>').prop({
                            'width' : 64,
                            'height' : 64,
                            'alt' : 'Голосовое управление',
                            'title' : 'Голосовое управление',
                            'src' : '/images/icons/microphonedisabled_8551.png'
                        })
                    )
                )
            }
        },

        init : function(config) {
            if(!('webkitSpeechRecognition' in window)) {
                misEngine.t('Not isset webkitSpeechRecognition Object in Browser');
                return -1;
            } else {
                this.webSpeechObj = new webkitSpeechRecognition();
            }
            if(this.config) {
                this.setConfig(config);
            }
            this.makeIcon();
            this.bindHandlers();
            return this;
        }
    };
});