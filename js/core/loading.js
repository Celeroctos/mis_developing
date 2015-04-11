var Core = Core || {};

(function(Core) {

    "use strict";

	var Loading = Core.createComponent(function(properties, selector) {
		Core.Component.call(this, properties, {
			image: url("/images/ajax-loader2.gif"),
			depth: 1000,
			width: 150,
			height: 25,
			velocity: "fast",
			color: "lightgray"
		}, selector);
	});

    Loading.prototype.render = function() {
		var imageWidth = this.property("width"),
			imageHeight = this.property("height"),
			height = this.selector().outerHeight(false),
			width = this.selector().outerWidth(false);
		if (this.hasOwnProperty("image")) {
			return void 0;
		}
		var index;
		if (!(index = parseInt(this.selector().css("z-index")))) {
			index = this.property("depth");
		} else {
			index += 1;
		}
		this.image = $("<img>", {
			css: {
				"position": "absolute",
				"height": imageHeight,
				"width": imageWidth,
				"left": "calc(50% - " + (imageWidth / 2) + "px)",
				"margin-top": height / 2 - imageHeight / 2,
				"z-index": index
			},
			src: this.property("image")
		});
		this.selector().before(this.back = $("<div>", {
			css: {
				"width": width,
				"height": height,
				"position": "absolute",
				"background-color": this.property("color"),
				"opacity": "0.5",
				"z-index": "100"
			}
		}).addClass(this.selector().attr("class")).fadeIn(this.property("velocity"))).before(
			this.image.fadeIn(this.property("velocity"))
		);
    };

	Loading.prototype.update = function() {
		this.render();
	};

	Loading.prototype.destroy = function() {
		this.reset(function() {
			Core.Component.prototype.destroy.call(this);
		});
	};

    Loading.prototype.reset = function(after) {
		var me = this;
		this.image.fadeOut(this.property("velocity"), function() {
			$(this).remove();
			delete me.image;
			after && after.call(me);
		});
		this.back.fadeOut(this.property("velocity"), function() {
			$(this).remove();
			delete me.back;
		});
    };

    Core.createPlugin("loading", function(selector, properties) {
		if (!$(selector).data("core-loading") || !$(selector).data("core-loading").image) {
			return Core.createObject(new Loading(properties, $(selector)), selector, true);
		} else {
			return void 0;
		}
	});

})(Core);