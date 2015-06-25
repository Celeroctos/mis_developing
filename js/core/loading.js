var Core = Core || {};

(function(Core) {

    "use strict";

	var Loading = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			image: url("images/ajax-loader2.gif"),
			width: 150,
			height: 25,
			velocity: "fast",
			color: "lightgray",
			opacity: 0.5,
            align: "center"
		}, selector);
		this.native = selector;
	});

    Loading.prototype.render = function() {
		var imageWidth = this.property("width"),
			imageHeight = this.property("height"),
			height = this.selector().outerHeight(false),
			width = this.selector().outerWidth(false);
		var index;
		if (this.property("depth")) {
			index = this.property("depth");
		} else if (!(index = parseInt(this.selector().css("z-index")))) {
			index = 1;
		}
		if (this.property("image") && (typeof this.property("image") == "string")) {
            var top = 0;
            if (this.property("align") == "center") {
                top = height / 2 - imageHeight / 2;
            } else if (this.property("align") == "top") {
                top = imageHeight / 2 + 50;
            }
			this.image = $("<img>", {
				css: {
					"position": "absolute",
					"height": imageHeight,
					"width": imageWidth,
					"left": "calc(50% - " + (imageWidth / 2) + "px)",
					"margin-top": top,
					"z-index": index + 1
				},
				src: this.property("image")
			});
		} else if (this.property("image")) {
			this.image = this.property("image").css({
				"position": "absolute",
				"height": imageHeight,
				"width": imageWidth,
				"left": "calc(50% - " + (imageWidth / 2) + "px)",
				"margin-top": height / 2 - imageHeight / 2,
				"z-index": index + 1
			});
		} else {
			this.image = $("<div>");
		}
		this.selector().before(this.back = $("<div>", {
			css: {
				"width": width,
				"height": height,
				"position": "absolute",
				"background-color": this.property("color"),
				"opacity": this.property("opacity"),
				"z-index": index
			}
		}).addClass(this.selector().attr("class")).fadeIn(this.property("velocity"))).before(
			this.image.fadeIn(this.property("velocity"))
		);
    };

	Loading.prototype.loading = function() {
		this.render();
	};
	Loading.prototype.update = function() {
	};
	Loading.prototype.destroy = function(success) {
		this.reset(success);
	};

    Loading.prototype.reset = function(success) {
		var me = this;
		this.image && this.image.fadeOut(this.property("velocity"), function() {
			if (me.back) {
				me.back.remove();
			}
			$(this).remove();
			success && success.call(this);
			Core.Component.prototype.destroy.call(me);
		});
		this.back && this.back.fadeOut(this.property("velocity"));
		if (!this.image) {
			Core.Component.prototype.destroy.call(me);
		}
    };

	Loading.prototype.widget = function(widget, attributes, success) {
		var me = this;
		this.loading();
		Core.loadWidget(widget, attributes, success)
			.success(function(json) {
				me.reset(function() {
					$(me.native).replaceWith(json["component"]);
				});
			}).fail(function() {
				me.reset();
			});
	};

	$.fn.loading = Core.createPlugin("loading", function(selector, properties) {
		if (!$(selector).data("core-loading") || !$(selector).data("core-loading").image) {
			return Core.createObject(new Loading(properties, $(selector)), selector, true);
		} else {
			return void 0;
		}
	});

})(Core);