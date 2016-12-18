$(document).ready(function() {
	$.widget('custom.art_analyser', {
		options:{
			liste_oeuvres_selector:'.oeuvres_critere',
			oeuvres_selector:'.oeuvre_vignette',
			oeuvres_children_selector:'.img_superpose.version_dia',
			action_numeroter:false,
			action_color_changer:true,
			action_diapo_compare:false,
			action_eZoom:false,
			action_preloader:false
		},
		_create:function() {
			var self=this;
			// For performance purpose, create a global object on page_load with all 'oeuvres_vignettes' blocks and references to children
			var oeuvres_object=$(this.options.oeuvres_selector);
			this.oeuvres_tri={};
			oeuvres_object.each(function(key, dom_elem) {
				var id_oeuvre=$(this).attr('id').slice(7);
				self.oeuvres_tri[id_oeuvre]={};
				self.oeuvres_tri[id_oeuvre].oeuvre=$(this);
				self.oeuvres_tri[id_oeuvre].children=$(this).find(self.options.oeuvres_children_selector);
			});
			if (this.options.action_numeroter===true) {this._init_renumeroter();}
			if (this.options.action_color_changer===true) {this._init_color_changer();}
			if (this.options.action_diapo_compare===true) {this._init_diapo_compare();}
			if (this.options.action_eZoom===true) {this._eZoom_init();}
			if (this.options.action_preloader===true) {this._preloader_init();}
		},
		_init_renumeroter:function() {
			  var listes=$(this.options.liste_oeuvres_selector);
			  listes.each(function() {
				  $(this).sortable({
					  update:function() {
					  	var nouvelle_num=$(this).sortable('toArray');
					  	var num=$('');
						$.each(nouvelle_num, function(key,value) {
							var ajouter=$('<input/>',{'name':'nouvelle_num\[\]', 'type':'hidden', 'value':value});
							num=num.add(ajouter);
							});
					  	$('#nouvelle_num_input').empty().append(num);
					  	$('#valider_numerotation').show();
				  		}
				  	}
				  );
			  });
			  listes.disableSelection();
		},
		_init_color_changer:function() {
			var self=this;
			// then affect event listeners on color blocks, querying the previously saved jQuery object to make changes
			$.each(this.oeuvres_tri, function(id, components) {
				components.oeuvre.find('.img_check_dia_color').on('mouseenter mouseleave',function (e) {
					if (e.originalEvent.type=='mouseover') {
						var version_selector=this.dataset.highlight.slice(9);
						components.oeuvre.find('canvas').css('opacity',0);
						components.children.show().css('opacity',0);
						components.children.filter('[data-highlight^='+version_selector+']').css('opacity',1);
					}
					else if (e.originalEvent.type=='mouseout') {
						components.oeuvre.find('canvas').css('opacity',1);
						components.children.css('opacity',0);
						components.children.filter('[data-highlight*=REF]').css('opacity',1);
					}
				});
			});
		},
		_init_diapo_compare:function() {
			var self=this;
			var checkboxes=$('.oeuvre_vignette_compare');
			checkboxes.each(function() {
				self._checkboxes_action($(this));
			});
			checkboxes.click(function() {
				self._checkboxes_action($(this));
			});
		},
		_checkboxes_action:function (that){
			var self=this;
			var url=$('#formulaire_comparer_action').attr('href');
			var id=that.attr('id').slice(15);
			var liste=$('#liste_comparaison');
			
			if (that.prop('checked')) {
				url=url+'&compare[]='+id;
				$('#formulaire_comparer_action').attr('href',url);
				var target=liste.find('h1');
				$('li#oeuvre_'+id).clone().insertAfter(target).find('.oeuvre_vignette_compare').click(function() {
					self._checkboxes_action($(this));
				});
			}
			else {
				var exp=new RegExp('&compare\\\[]='+id); // the brackets is escaped when creating the string, then we need to add an escaped backslash to escape the brackets in the regexp object (weird, isn't it ?)  
				url=url.replace(exp,'');
				$('#formulaire_comparer_action').attr('href',url);
				liste.find('li#oeuvre_'+id).remove();
				$('li#oeuvre_'+id).find('.oeuvre_vignette_compare').prop('checked', false);
			}
		},
		_eZoom_init:function() {
			var that=this;
//			var elem=this.element.slice(0); // reference the element at the state before jQuery returns it to the variable (this actually works, but isn't what we want)
			$.each(this.oeuvres_tri, function(id, components) {
				components.oeuvre.elevateZoom({
					zoomType: "lens", 
					containLensZoom: true,
					lensSize:555,
					scrollZoom:true,
					onZoomedImageLoaded: function() {
						that.element.trigger('zoomReady');
					}
//					onComplete:function() {} // won't work : not called in the plugin, although it's part of the option set
				});
			});
		},
		_preloader_init:function() {
			var first=true, self=this;
			console.log('Début du chargement différé des images');
			var loadingIdent = $('#catalogue_menu #info_preload').append($('<h4/>', {style:'margin : 24px;'}).html('...Chargement des images...'));
			
			$.each(this.oeuvres_tri,function(id, components) {
				  var urls=components.children.find('.img_postLoader');
				  urls.each(function(key,dom_elem) {
					  $(this).one('load', function () {
						  if (key+1<urls.length) {
							  var next=$(urls[key+1]);
							  var nextUrl=next.attr('data-url');
							  next.attr('src',nextUrl);
						  }
						  else {
							  self.element.trigger('loadNextImg');
						  }
					  });
					  if (first) {
						  var url=$(this).attr('data-url');
						  $(this).attr('src',url);
						  first=false;
					  }
				  });
			  });
			function filter_callback(value) {
				if (typeof value.dataset.url!=='undefined') {
					if (value.dataset.url.indexOf('local/cache-vignettes')!=-1) {
						return true;
					}
				}
			}
			self.element.on('loadNextImg', function () {
				var loc=window.location;
				var done=false;
				var images_list=Array.prototype.slice.call(document.images).filter(filter_callback);
				$.each(images_list, function (key, dom_elem) {
					if (window.location==this.src && done!=true) {
					  $(this).attr('src',this.dataset.url);
					  if (key % 5 === 0) {
						  var dots = '';
						  for (var i = 0; i < (key % 3 + 1); i++)
							  dots += '.';
						  loadingIdent.find('h4').html('...Chargement des images' + dots);
					  }
					  done=true;
					  if (key==images_list.length-1) {
						  console.log('Chargement différé des images complet');
						  loadingIdent.animate({opacity:0}, {duration: 1024});
						  var nav=self.element.siblings('#catalogue_menu').find('.anchor_container');
//						  nav=nav.add(self.element.siblings('#to_page_top'))
						  $('.compare_link').animate({'opacity':.22},1024, 'easeInOutCubic');
						  nav.show().animate({'opacity':1},1024, 'easeInOutCubic');
					  }
					}
				})
			});
		}
	});
});