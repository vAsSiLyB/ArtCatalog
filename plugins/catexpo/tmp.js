$.getScript('../plugins/sb_spipUtils/jQuery_utils/math.js').done(function(){}).fail(function() {
	$.getScript('plugins/sb_spipUtils/jQuery_utils/math.js').done(function(){}).fail(function() {
			console.log('impossible de charger math.js');
		});
});

//Get the first property
function getFirstprop(object) {for (prop in object) {return object[prop];}};

$(document).ready(function () {
	// IE < 11 is forbidden
	if (navigator.userAgent.match(/MSIE 8/) || navigator.userAgent.match(/MSIE 9/) || navigator.userAgent.match(/MSIE 10/)) {
		return;
	}
	
	$.widget('custom.soundplayer', {
		auto_mode:0,
		auto_diods:{},
		audioBuffer:new ArrayBuffer(),
		buffer:null, // Decoded audio buffer
		startTime:0,
		currentPosition:0,
		autoFactor:{1:1,2:1,3:1,4:1},
		auto_mode_factor_input:{},
		auto_off_diods:{},
		frequency_values:{},
		_create: function() {
			$(document).bind('dragover drop', function(e) {
				e.originalEvent.preventDefault();
			});
			var that=this;
			
			this.light_mixer=$('#mix_controler').data('highlight');
			
			this.dropBox=$('<div/>', {'class':"drop_box"}).appendTo(this.element);
			this.display=$('<div/>', {'class':"display"}).appendTo(this.element);
			this.waiting_logo=$('<canvas/>', {id:"graphic_display"}).appendTo(this.display);
			this.dropBox.bind('dragenter dragover dragleave drop dblclick', function(e) {
//				e.preventDefault();
				e.originalEvent.preventDefault();
			}).bind('dragenter', function(e) {
					$(this).empty();
					$(this).addClass('dragover');
					that.dropAttempt=true;
			}).bind('dragleave', function(e) {
					$(this).removeClass('dragover');
					that.dropAttempt=false;
//					that.update_position(0);
			}).bind('mouseout', function(e) {
				$(this).removeClass('dragover');
			}).bind('drop', function(event) {
				if (that.element.hasClass('active')) {
					$(this).addClass('enable');
					that.transport_field.addClass('activable');
					that.numeric_container.addClass('activable');
					if (window.File){
						// Load new sound
						var reader = new FileReader();
						var e=event.originalEvent;
						that.audiofile=e.dataTransfer.files[0];
						that.track_title=that.audiofile.name.slice(0,7);
						// Onload  is fired when the file has been successfully read
						reader.onload = function(e){
							var a=new ArrayBuffer();
							that.audioBuffer=e.target.result;
							that.audioBuffer_playing=that.audioBuffer.slice(0);
							that.buffer=false;
							that.currentPosition=0;
							that.update_position();
							console.log('file loaded');
							that.dropAttempt=false;
							that.sound=that._create_sound();
							that.sound.playState='stopped';
						};
						reader.readAsArrayBuffer(e.dataTransfer.files[0]);
						
//						that.sound=that._create_sound(that.audiofile, true);
					}
				}
			});
			
			this.transport_field=$('<div/>', {id:"transport_field"}).appendTo(this.element);
			this.numeric_container=$('<div/>', {id:'numeric_container'}).appendTo(this.transport_field);;
			this.numeric_field=$('<input/>', {id:"numeric_field", type:'text'}).appendTo(this.numeric_container);
			this.progress_bar=$('<div/>', {id:"progress_bar"}).appendTo(this.transport_field);
			this.progress_bar_inner=$('<div/>', {id:"progress_bar_inner"}).appendTo(this.progress_bar);
			
			this._construct_sound_button();
			this.load_playlist();
			this.activate_playlist();
			this._construct_auto_button();

			// Allow user keyboard input to set currentPosition
			this.numeric_field.on('keyup', function(e) {
				// remove dots
				var dummy=$(this).val().replace(/:/g,'');
				// Get the array
				var value=new Array();
				value=[dummy.slice(-2),dummy.slice(-4,-2),dummy.slice(-6,-4)];
				if (e.keyCode!==13) {
					// put back the dots
					value=value.reverse();
					$(this).val(value.join(':').replace(/^(::|:)/,''));
				}
				else {
					that.stop_button.click();
					value=that._convert_time(value,'sexToDec');
					that.currentPosition=value;
					if (that.sound) {
						that.sound.playState='paused';
						that.updateLoop_started=false;
						that.play_button.click();
					}
					$(this).fadeToggle({complete:fadeToggle_complete});
				}
			});
			
			// Allow user mouse click on progress_bar to set currentPosition
			this.progress_bar.click(function(e) {
				if (that.duration) {
					if (e.pageX || e.pageY) { 
						  x = e.pageX;
						  y = e.pageY;
						}
					else { 
					  x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft; 
					  y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop; 
					} 
					var offset_left=$(this).offset().left;
					var width=$(this).width();

					if (that.sound.playState=='playing') {
						that.stop_button.click();
						that.currentPosition=((x-offset_left)/width)*that.duration;
						that.startTime=that.audio_ctx.currentTime-that.currentPosition;
						that.sound.playState='paused';
						that.play_button.click();
					}
				}
			});
			
			// Activate user keyboard input on dblclick
			var click_object=this.dropBox.add(this.transport_field);
			click_object.dblclick(function() {
				var InOrOut;
				if (!that.transport_field.hasClass('input_active')) {
					that.transport_field.addClass('input_active');
					that.numeric_container.addClass('input_active');
					InOrOut='In';
				}
				that.numeric_field.fadeToggle({complete:fadeToggle_complete(InOrOut)});
				that.numeric_field.val('');
				that.numeric_field.focus();
			});
			
			function fadeToggle_complete(InOrOut) {
				var InOrOut=InOrOut?InOrOut:false;
				if (that.transport_field.hasClass('input_active') && InOrOut!='In') {
					that.numeric_container.removeClass('input_active');
					that.transport_field.removeClass('input_active');
				}
			}
//			this._draw_waiting_logo(2);
		},
		load_playlist:function() {
			that=this;
			this.playlist=$("[data-highlight*='playlist']");
			this.playlist=this.playlist.clone().addClass('playlist_container').appendTo(this.element).show();
			this.playlist_files=this.playlist.find('[data-highlight*="audiofile"]');
			this.playlist_files.click(function(e) {
				e.originalEvent.preventDefault();
			});
			$('<span/>', {'class':''}).html('Playlist <br/>').prependTo(this.playlist);
		},
		// activate playlist and auto diods on startup
		activate_playlist:function() {
			that=this;
			this.element.on('cssClassChanged', function() {
				if (that.element.hasClass('active')) {
					that.playlist_files.click(function(event_object) {
						that.dropBox.addClass('enable');
						var elem=$(this);
						var xhr = new XMLHttpRequest();
						xhr.open('GET', this.href, true);
						xhr.responseType = 'arraybuffer';
						xhr.onload = function(xhr) {
							if (this.status == 200) {
								that.audioBuffer=this.response
								that.audioBuffer_playing=that.audioBuffer.slice(0);
								var filename=elem.text();
								if (filename.slice(0,1)===' ') {
									filename=filename.slice(1);
								}
								that.track_title=filename.slice(0,7);
								that.buffer=null;
								that.currentPosition=0;
								that.update_position();
								that.sound=that._create_sound();
								that.sound.playState='stopped';
								that.transport_field.addClass('activable');
								that.numeric_container.addClass('activable');
							}
						};
						xhr.send();
					});
					// Auto_diods must light up
					$.each(that.auto_diods, function(key, elem) {
						that.auto_off_diods[key].off=that.auto_off_diods[key].removeClass('off').off=false;
					});
					
				}
				else {
					// Auto_diods must light down
					$.each(that.auto_diods, function(key, elem) {
						that.auto_off_diods[key].off=that.auto_off_diods[key].addClass('off').off=true;
					});
				}
			});
		},
		_construct_auto_button:function() {
			this.auto_button_container=$('<div/>', {'class':'auto_button_container'}).appendTo(this.element);
			$('<div/>', {'class':'title'}).html('Auto modes ').appendTo(this.auto_button_container);
			
			// Define faders for the rest of the app
	    	this.faders=that.light_mixer.controls.sliders;
			// Retrieve versions
			var versions=getFirstprop(that.light_mixer.versions).MAIN;
			// get rid of fluo2, fluo3... versions
			this.versions_utiles={};
			var index=1;
			$.each(versions, function(key, version) {
				if (parseInt(version.slice(-1))==1 || !parseInt(version.slice(-1))) {
					that.versions_utiles[index]=version;
					index++;
				}
			});
			
			// Set default sliders value for each auto mode
	    	var default_case=12*that.autoFactor[1];
	    	var default_values;
	    	this.element.on('auto_mode_change', function() {
				switch (that.auto_mode) {
					case 1: default_values=[default_case,default_case,default_case,default_case]; break;
					case 2: default_values=[default_case,default_case,default_case,default_case]; break;
					case 3: default_values=[default_case,default_case,default_case,default_case]; break;
					case 4: default_values=[123,77,38,77]; break;
				}
				$.each(that.versions, function(key, version) {
					that.faders[version].slider('value',default_values[key]);
				});
	    	})
			
			this.auto_button=$('<div/>', {'class':'auto_button'}).appendTo(this.auto_button_container).click(function() {
				if (that.element.hasClass('active')) {
					if (that.auto_diods[that.auto_mode]) {
						that.auto_diods[that.auto_mode].removeClass('on');
					}
					if (that.auto_diods[that.auto_mode+1]) {
						that.auto_diods[that.auto_mode+1].addClass('on');
						that.auto_mode++;
					}
					else {
						that.auto_mode=0;
					}
				}
			});
			
			this.auto_diods[1]=$('<div/>', {'class':'auto_diod'}).html('<span>1</span>').appendTo(this.auto_button_container);
			this.auto_diods[2]=$('<div/>', {'class':'auto_diod'}).html('<span>2</span>').appendTo(this.auto_button_container);
			this.auto_diods[3]=$('<div/>', {'class':'auto_diod'}).html('<span>3</span>').appendTo(this.auto_button_container);
			this.auto_diods[4]=$('<div/>', {'class':'auto_diod'}).html('<span>4</span>').appendTo(this.auto_button_container);
			
			// Allow user to set autoFactor and to enable/disable some versions from auto lighting
			function toggle_diod(key) {
				if (!that.auto_off_diods[key].off) {
					that.auto_off_diods[key].addClass('off');
					that.auto_off_diods[key].off=true;
					that.faders[that.versions_utiles[key]].slider('value',12);
				}
				else {
					that.auto_off_diods[key].removeClass('off');
					that.auto_off_diods[key].off=false;
				}
			}
			$.each(this.auto_diods, function(key, elem) {
				that.auto_off_diods[key]=$('<div/>', {'class':'auto_diod_green off'}).appendTo($(this)).click(function(){
					if (that.element.hasClass('active')) {
						toggle_diod(key);
					}
				});;
				that.auto_mode_factor_input[key]=$('<input/>', {'class':'auto_diod_after'}).val('1').appendTo($(this))
					.wrap($('<div/>', {'class':'auto_diod_after'}));
				that.auto_mode_factor_input[key].on('keyup', function(e) {
					if (e.keyCode==13) {
						that.autoFactor[key]=$(this).val();
					}
				});
			});
			this.auto_factor_container=$('<p/>', {'class':''}).append($('<span/>',{'class':''}).html('ON F')).appendTo(this.auto_button);
			
			$(document).on('keydown', function(e) {
				if (!(e.keyCode==82 && e.ctrlKey) && e.keyCode!=123) { // Allow Ctrl+R & F12
					e.originalEvent.preventDefault();
				}
				var value;
				if (that.element.hasClass('active')) {
					
					switch (e.keyCode.toString()) {
						case '49': toggle_diod(1);
							break;
						case '50': toggle_diod(2);
							break;
						case '51': toggle_diod(3);
							break;
						case '52': toggle_diod(4);
							break;
						case '53': $.each(that.auto_off_diods, function(key,diod) {that.auto_off_diods[key].off=that.auto_off_diods[key].addClass('off').off=true;}); 
							that.auto_mode=value=4;
							$.each(that.auto_diods, function(key, diod) {if (key==value) diod.addClass('on'); else diod.removeClass('on');});
							break;
						case '65': that.auto_mode=value=1;
							$.each(that.auto_diods, function(key, diod) {if (key==value) diod.addClass('on'); else diod.removeClass('on');});
							break;
						case '90': that.auto_mode=value=2;
							$.each(that.auto_diods, function(key, diod) {if (key==value) diod.addClass('on'); else diod.removeClass('on');});
							break;
						case '69': that.auto_mode=value=3;
							$.each(that.auto_diods, function(key, diod) {if (key==value) diod.addClass('on'); else diod.removeClass('on');});
							break;
						case '82': that.auto_mode=value=4;
							$.each(that.auto_diods, function(key, diod) {if (key==value) diod.addClass('on'); else diod.removeClass('on');});
							break;
						case '107': $.each(that.autoFactor, function(key, factor) {
								value=that.autoFactor[key]=Number(factor)+.1;
								that.auto_mode_factor_input[key].val(Math.round10(value,-1));
							});
							break;
						case '109': $.each(that.autoFactor, function(key, factor) {
								value=that.autoFactor[key]=Number(factor)-.1;
								that.auto_mode_factor_input[key].val(Math.round10(value,-1));
							});
							break;
						case '32': // allow play by space bar
								if(that.sound.playState=='stopped' || that.sound.playState=='paused') that.play_button.click();
								else if (that.sound.playState=='playing') that.stop_button.click();
							break;
						default:break;
					}
					
				}
			});
		},
		_construct_sound_button: function () {
			var that=this;
			this.sound_buttons=$('<div/>').addClass('sound_buttons').appendTo(this.element);
			this.stop_button=$('<div/>').addClass('sound_button button_pause').appendTo(this.sound_buttons);
			this.stop_button.click(function() {
				if (that.sound) {
					that.audioBuffer_playing=that.audioBuffer.slice(0); // avoid reference by using a dummy function on the variable
					if (that.sound.playState=='playing') {
						that.sound.stop();
						that.sound=that._create_sound();
						that.sound.playState='paused';
					}
				}
			});
			this.play_button=$('<div/>').addClass('sound_button button_play').appendTo(this.sound_buttons);
			this.play_button.click(function() {
			this.smoothing_guide=0;
				if (that.element.hasClass('active') && that.sound && that.sound.playState=='stopped') {
					that.startTime=that.audio_ctx.currentTime;
					that.sound.start();
					that.sound.playState=that.buffer?'playing':'request_play';
					// if the user pressed the play button after buffering (else we've got to intercept it in the _connect() function)
					if (that.sound.playState=='playing') {
						that.startTime=that.audio_ctx.currentTime;
						that.update_position(0);
						console.log('playing');
					}
					requestAnimationFrame(that._update_loop);
				}
				else if (that.element.hasClass('active') && that.sound && that.sound.playState=='paused') {
					that.startTime=that.audio_ctx.currentTime-that.currentPosition;
					that.sound.start(0, that.currentPosition);	
					that.sound.playState='playing';
					if (that.updateLoop_started!=true) {
						requestAnimationFrame(that._update_loop);
					}
				}
			});
		},
		_create_audio_context:function() {
		      var AudioContext = window.AudioContext || window.webkitAudioContext;
		      var context=new AudioContext(); // Create audio container
		      return context;
		},
		_create_sound:function() {
			var that=this;
			
			// Stop any playing sound first
			if (that.sound && that.sound.playState=='playing') { // check playstate to prevent InvalidStateError : cannot call stop without calling start first
				that.sound.stop();
				that.sound.playState='paused';
			}
			
			if (typeof this.audio_ctx=='undefined') {
				this.audio_ctx=this._create_audio_context();
			}
			var source = this.audio_ctx.createBufferSource();
			
			// Prepare to catch ended message (problem : "ended" is fired when the player is paused)
			source.addEventListener('ended', function(e) {
//				console.log('paused');
			});
			
			// Buffering
			if (this.buffer) { // Audio has already been decoded
				source.buffer=this.buffer;
				that._connect_sound(source);
			}
			else if (this.audio_ctx.decodeAudioData) { // We need to decode audio
				// Buffering show_pseudo_progress
				console.log('buffering...');
				var init_time=new Date();
				init_time=init_time.getTime();
				this._show_pseudo_progress(init_time);
				
				//decode the audio data
				this.audio_ctx.decodeAudioData(that.audioBuffer_playing,function(buffer){
					that.buffer=source.buffer=buffer;
					that.duration=buffer.duration;
					console.log('buffering complete');
					that._connect_sound(source);
				});
			} 
			else {
				//fallback to the old API
				source.buffer = this.audio_ctx.createBuffer(that.audioBuffer_playing,true);
			}
			return source;
		},
	    _audio_routing:function (source) {
	    	var that=this;
	    	console.log('connecting to analyser');
	    	this.analyser = this.audio_ctx.createAnalyser();
	    	this.analyser.fftSize=2048;
	    	this.analyser.smoothingTimeConstant=.7;
	    	var bufferLength = this.analyser.frequencyBinCount;
	    	this.frequencyDataArray = new Uint8Array(bufferLength);
	    },
	    _connect_sound:function(source) {
	    	var that=this;
			// if the user pressed the "play" button during buffering, update position will run with a wrong start value : reset it
			if (that.sound.playState=='request_play') {
				that.startTime=that.audio_ctx.currentTime;
				console.log('playing');
				that.sound.playState='playing';
			}
			// else just reset the counter, if we're not in paused state
			else if (that.sound.playState=='stopped') {
				that.update_position(0);
			}
			that._audio_routing();
			source.connect(that.analyser);
			that.analyser.connect(that.audio_ctx.destination); // Connect sound source to output
			return source;
	    },
		_update_loop:function () {
			if (that.buffer && that.sound.playState=='playing' && that.currentPosition<=that.duration) {
				//Audio Stuff
				that.currentPosition=that.audio_ctx.currentTime-that.startTime;
				that.update_position(that.currentPosition);
				that._update_frequency_data();
				that.updateLoop_started=true;
				
				// Fluo_light stuff
				that.smoothing_guide++;
				if (i%1==0) { // rough time smoothing (as we can see : not used)
					// Special auto_mode
					if (that.auto_mode==4) {
		    	    	localStorage.setItem('original_frequency_values',JSON.stringify(that.frequency_values));
		    	    	that.light_mixer.element.trigger('send_special_auto');
					}
					else if (that.auto_mode!=0) {
		    	    	//normal auto_mode
						that._send_to_parent();
					}
				}
			}
			else if (that.currentPosition>that.duration) {
				that.currentPosition=0;
				that.update_position(0);
				console.log('ended');
				that.sound=that._create_sound();
				that.sound.playState='stopped';
				return;
			}
			requestAnimationFrame(that._update_loop);
		},
		update_position: function (value) {
			var that=this;
			var that=this;
			this.display.css('background','none');
			this.dropBox.empty();
			if (value!==undefined) {
				var time=Math.round(value);
			}
			else {
				var time=Math.round(that.currentPosition);
			}
			var h,m,s;
			var time_array=this._convert_time(time,'decToSex');
			s=time_array[0]?time_array[0]:'00';
			m=time_array[1]?time_array[1]:'00';
			h=time_array[2]?time_array[2]:'00';
			var line1=$('<div/>').text(that.track_title);
			var line2=$('<div/>').text(h+':'+m+':'+s);
			if (!that.dropAttempt) {
				that.dropBox.append(line1.add(line2));
			}
			that._update_progress_bar();
		},
		_update_progress_bar:function() {
			var weighted_value=this.progress_bar.width()*this.currentPosition/this.duration;
			this.progress_bar_inner.width(weighted_value);
		},
		_convert_time: function(time,type) {
			if (type=='decToSex') {
				var s = ('0'+time%60).slice(-2);
				var m = ('0'+Math.floor(time/60)%60).slice(-2);
				var h = ('0'+Math.floor(time/(60*60))%24).slice(-2);
				var retour=[s,m,h];
				return retour;
			}
			else if (type=='sexToDec') {
				if (Object.prototype.toString.call(time) == '[object String]' ) {
					var time_array=time.split(':');
					time_array=time_array.reverse();
				}
				else {
					time_array=time;
				}
				var h,m,s;
				h=m=s=0;
				for (var fraction in time_array) {
					if (!isNaN(time[fraction]) && time[fraction].length!=0) {
						switch (fraction) {
							case '0': s=parseInt(time[fraction]);
							case '1': m=parseInt(time[fraction]);
							case '2': h=parseInt(time[fraction]);
						}
					}
					else {
						switch (fraction) {
							case '0': s=parseInt('00');
							case '1': m=parseInt('00');
							case '2': h=parseInt('00');
						}
					}
				}
				var dec=s+(m*60)+(h*60*60);
				return dec;
			}
		},
		_show_pseudo_progress:function(init_time) {
			var that=this;
			var current_time, diff;
			
			this.dropBox.empty();
			
			function step() {
				current_time=new Date();
				current_time=current_time.getTime();
				diff=(current_time-init_time)/3000;
				that._draw_waiting_logo(diff);
				
				if (!that.buffer) {
					requestAnimationFrame(step);
				}
				else {
					that.display.empty();
					that.waiting_logo=$('<canvas/>', {id:"graphic_display"}).appendTo(that.display);
				}
			}
			requestAnimationFrame(step);
		},
		_draw_waiting_logo:function(step) {

			this.display.css('background','none');
		    var canvas = document.getElementById('graphic_display');
		    canvas.width=77;
		    canvas.height=47;
		    var context = canvas.getContext('2d');
		      
		    // arc() doesn't accept float coordinates
		    var x = Math.round(canvas.width / 2)-4;
		    var y = Math.round(canvas.height / 2);
	
		    var radius = 21;
		    var startAngle = 0;
		    var endAngle = step * 2 * Math.PI;
		    var counterClockwise = false;
	
		    context.beginPath();
		    context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
		    context.lineWidth = 12;
	
		    // line color
		    context.strokeStyle = 'orange';
		    context.stroke();
		},
		_update_frequency_data:function() {
	    	var that=this;
			this.analyser.getByteFrequencyData(that.frequencyDataArray);
//			console.log(that.frequencyDataArray);
		    var canvas = document.getElementById('graphic_display');
		    canvas.width=77;
		    canvas.height=47;
		    var context = canvas.getContext('2d');
		    var grad=context.createLinearGradient(0,canvas.height,0,0);
		    grad.addColorStop(0,"#1A1");
		    grad.addColorStop(0.77,"#AA1");
		    grad.addColorStop(1,"#A11");
		    context.fillStyle = grad;
		    
		    // group frequency data by octave, then smooth (average)
		    var mean, sum;
		    var offset=0
		    var index=0
		    var octave_step=4;
		    var tampon=new Array();
		    var bars=0;
			$.each(that.frequencyDataArray, function(key, value) {
				tampon.push(value);
				if (key == octave_step) {
					mean=0;
					sum=0;

					for (var val in tampon) {
						sum=sum+parseInt(tampon[val]);
					}
					mean=sum/tampon.length;
					
				    grad.addColorStop(0,"#"+Math.floor(mean/26)+"A1");
				    context.fillStyle = grad;
					context.fillRect(offset, canvas.height, 9, -mean/5.5);
					that.frequency_values[index]=mean;
					
					offset=offset+9;
					index++;
					tampon=new Array();
					if (key!=0) {
						octave_step=octave_step*2;
					}
				}
			});
	    },
	    // move the sliders
	    _send_to_parent:function() {
	    	// The solution chosen here impact refresh of mixer automaticaly (by moving the sliders)
	    	// Any other solution would have meant catching light_mixer.element.trigger('sendUpdate');
	    	var that=this;
	    	var frequency_values={};
	    	var indexes=[0,3,5,7];
	    	
	    	if (this.auto_mode!=0) {
		    	var index=0;
		    	$.each(that.faders, function(key, widget) {
		    		if (index==1 && (that.auto_mode==2 || that.auto_mode==3) && !that.auto_off_diods[index+1].off) {
		    			widget.slider('value', that.frequency_values[indexes[0]]*that.autoFactor[index+1]/2.3);
//		    			frequency_values[index]=that.frequency_values[indexes[1]];
		    		}
		    		else if (that.auto_mode==1 && index==0 && !that.auto_off_diods[index+1].off) {
		    			widget.slider('value', that.frequency_values[indexes[0]]*that.autoFactor[index+1]/2.3);
		    		}
		    		else if ((that.auto_mode==1 || that.auto_mode==2) && index==2 && !that.auto_off_diods[index+1].off) {
		    			widget.slider('value', that.frequency_values[indexes[3]]*that.autoFactor[index+1]*1.3);
		    			// (not-used) frequency value doesn't follow the same logic : 0 is fluo, 2 is green, 3 is gold => pseudo-FFT effect in the parent window
//		    			frequency_values[index]=that.frequency_values[indexes[3]];
		    		}
		    		else if (index==3 && that.auto_mode==3 && !that.auto_off_diods[index+1].off) {
		    			widget.slider('value', that.frequency_values[indexes[1]]*that.autoFactor[index+1]/1.7);
//		    			frequency_values[index]=that.frequency_values[indexes[2]];
		    		}
		    		else if (that.auto_mode==4 && !that.auto_off_diods[index+1].off) {
			    		if (index==0) {
			    			widget.slider('value', that.frequency_values[indexes[0]]*that.autoFactor[index+1]/2.3);
			    		}
			    		else if (index==1) {
			    			widget.slider('value', that.frequency_values[indexes[0]]*that.autoFactor[index+1]/2.3);
			    		}
			    		else if (index==2) {
			    			widget.slider('value', that.frequency_values[indexes[3]]*that.autoFactor[index+1]*1.3);
			    		}
			    		else if (index==3) {
			    			widget.slider('value', that.frequency_values[indexes[1]]*that.autoFactor[index+1]/1.7);
			    		}
		    		}
//		    		else if (index==0) {
//		    			frequency_values[index]=that.frequency_values[indexes[0]];
//		    		}
		    		index++;
		    	});
	    	}
	    	// (not used)
//	    	localStorage.setItem('frequency_values',JSON.stringify(frequency_values));
	    }
	}	
	);
	$.widget('custom.highlight', {
		oeuvres:{},
		documents:{},
		versions:{},
		controls: {
			sliders:{},
			key_overrides:{},
			buttons:{},
			sliders_real_values:{},
			sliders_override_values:{},
			fader_control_buttons:{},
			fader_override_buttons:{},
			midi_device:{},
			midi_override_controls:{},
			midi_override_values:{},
			value_tester:{},
			full_mixer:false,
			MIDI_DEBUG_mode:false,
			remote_active:false,
			faders_inhibit:false,
		},
		_create: function () {
			var that=this;
			this.versions=JSON.parse(sessionStorage.versions);

			if (!this.versions) {
				console.log('error loading versions');
				$('#link_light').addClass('error');
				return;
			}

			if (window.location.href.match(/mode=standard/g)) {
				console.log('standard_mode');
				this.versions={"0":{"REF":{"0":"version_REF_jour"},"MAIN":{"1":"version_fluo1","2":"version_fluo2","3":"version_fluo3","4":"version_rouge","5":"version_or","6":"version_vert"}}};
			}

			if (this.versions) {
				// La console de mixage, il ne doit y en avoir qu'une
				this.controls.conteneur=this.element.find("#mixer");
				this._construct_mixer();				
				this._construct_midi_mapper();
				this._affect_faders();
				this._construct_full_mixer_button();
				this.show_full_mixer();
//				this.ref_enable_button.click();
				this._compute_mix();
			}
		},
		_construct_mixer : function () {
			var that=this;
			// Conteneur
			this.faders_container=$('<div/>',{id:'fader_container'}).appendTo(this.controls.conteneur);

			// XY Controler
//		    $('<div/>',{'class':'clearfix'}).prependTo(this.faders_container);
			this.controler_container=$('<div/>',{'class':'controler_container'}).appendTo(this.faders_container);
			this.xy_controler=$('<div/>',{'class':'xy_controler'}).appendTo(this.controler_container);
			
			// Sliders
			this.faders=$('<div/>',{'class':'faders clearfix'}).appendTo(this.faders_container);
//			that.controls.version_handlers=JSON.parse(sessionStorage.version_handlers);
//			var version=
			$.each(this.versions, function (key, version) {
				$.each(version.MAIN, function (index, version) {
//					that.controls.version_handlers[version]=$('[data-highlight*="'+version+'"]');
					// Les versions se terminant par un nombre > 1 doivent �tre trait�es en fader progressif
					// Pour les autres, on attribue un fader
					if (version.substr(0,3)!=='mix' && (isNaN(parseInt(version.substr(-1))) || version.substr(-1)=='1')) {
						// Si le fader n'est pas déjà créé
						if (typeof that.controls.sliders[version]==='undefined') {
							var slider_container=$('<div/>',{'class':'slider_container '+version}).appendTo(that.faders);
							that.controls.value_tester[version]=$('<div/>',{'class':'fader_test_light_lit'}).prependTo(slider_container);
							$('<div/>',{'class':'fader_test_light_base'}).prependTo(slider_container);
							that.controls.sliders[version]=$('<div/>',{'class':'version_slider'}).appendTo(slider_container);
						}
					}
				});
			});

//			$('<div/>',{'class':'clearfix'}).appendTo(this.faders_container);
			
			// Boutons // ---> (After slider definition, they depend on it)
//			$('<div/>',{'class':'clearfix'}).appendTo(this.faders_container);
			this.button_container=$('<div/>',{'class':'button_container'}).prependTo(this.faders);
			var serigraph_1=['W','X','C','V'];
			var keystroke_1=[87,88,67,86];
			var i=0;
			
			$.each(that.controls.sliders, function(version,slider) {
				that.controls.fader_override_buttons[version]=$('<div/>',{'class':'keyboard_button lit'}).text(serigraph_1[i]).appendTo(that.button_container);
				that.controls.key_overrides[version]=keystroke_1[i];
				i++;
			});
		    //disable the default browser action for keydown
		    $(document).bind('keydown', function (e) {
		    	$.each(keystroke_1,function(key,value){
			    	if (e.keyCode==value) {
			    		e.preventDefault();
			    	}
		    	});
		    });
//			var i=0;
//			$('<div/>').addClass('clearfix').appendTo(that.button_container);
//			$.each(that.controls.sliders, function(version,slider) {
//				that.controls.fader_control_buttons[version]=$('<div/>').addClass('keyboard_button corner').text(serigraph_1[i]).appendTo(that.button_container);
//				i++;
//			});

		    this.controls.right_side_conteneur=$('<div/>',{id:'right_side_container'}).appendTo(this.controls.conteneur);
			// ON/OFF Buttons
			this.ref_enable_button=$('<div/>',{'class':'show_on_button'}).appendTo(this.controls.right_side_conteneur);
			this.midi_enable_button=$('<div/>',{'class':'show_on_button'}).appendTo(this.controls.right_side_conteneur);
			
			// Midi Controls
			$('<meta/>',{content:'requiresActiveX=true'}).attr('http-equiv','X-UA-Compatible').prependTo('head');
			var jazz_object_html='<object id="Jazz1" classid="CLSID:1ACE1618-1C7D-4561-AEE1-34842AA85E90" class="hidden"> <object id="Jazz2" type="audio/x-jazz" class="hidden"></object></object>';
			$('<p/>', {'class':'hidden', id:'jazz_link'}).html('MIDI functions of the mixer requires the <a href=http://jazz-soft.net>Jazz-Plugin</a>').appendTo(this.controls.right_side_conteneur);
			this.midi_device_container=$('<div/>',{'class':'midi_device'}).appendTo(this.controls.right_side_conteneur);
			this.midi_device_container.append(jazz_object_html);
			
			setTimeout(function(){
				that.Jazz = document.getElementById("Jazz1"); 
				if(!that.Jazz || !that.Jazz.isJazz) that.Jazz = document.getElementById("Jazz2");
				if(!that.Jazz || !that.Jazz.isJazz) $('#jazz_link').removeClass('hidden');
				// First Opening at Page load
//				that.controls.midi_device.name=that.Jazz.MidiInOpen(0);
//				that.midi_device_container.texte=$('<div/>').text(that.controls.midi_device.name.slice(0,12)).prependTo(that.midi_device_container).hide();
//				that.Jazz.MidiInList();
				},500);
			
			// Sound Player
			this.soundplayer_container=$('<div/>',{id:'soundplayer_container'}).appendTo(this.controls.right_side_conteneur);
			this.soundplayer_container.soundplayer();
			
			// Automation zone
			this.automation_zone=$('<div/>', {id:"automation_zone"}).appendTo(this.controls.right_side_conteneur);
			$('<span/>', {'class':""}).html('Automation').appendTo(this.automation_zone);
			this.automation_record=$('<div/>', {id:'automation_record'}).appendTo(this.automation_zone);
			this.automation_record_button=$('<div/>', {'class':'automation_record_button'}).appendTo(this.automation_record);
			$('<span/>', {'class':"button_text"}).html('Record').appendTo(this.automation_record_button);
			$('<div/>', {'class':"button"}).appendTo(this.automation_record_button).click(function() {
				if (that.ref_enable_button.hasClass('enable') && that.soundplayer_container.data('soundplayer').buffer) {
					if (!that.automation_diod.hasClass('active')) {
						that.automation_diod.addClass('active');
						that.controls.record_on=true;
						that._automation_record('start');
					}
					else {
						that.automation_diod.removeClass('active');
						that.controls.record_on=false;
						that._automation_record('stop');
					}
				}
				// Blink if no track loaded
				else if (that.ref_enable_button.hasClass('enable') && !that.soundplayer_container.data('soundplayer').buffer) {
					that.automation_diod.addClass('warning');
					setTimeout(function() {
						that.automation_diod.removeClass('warning');
						setTimeout(function() {
							that.automation_diod.addClass('warning');
							setTimeout(function() {
								that.automation_diod.removeClass('warning');
							},127);
						},127);
					},127);
				}
			});
			this.automation_diod=$('<div/>', {'class':"auto_diod_red"}).appendTo(this.automation_record_button);
			this.automation_save_button=$('<div/>', {'class':'automation_record_button'}).appendTo(this.automation_record);
			$('<span/>', {'class':"button_text"}).html('Save').appendTo(this.automation_save_button);
			$('<div/>', {'class':"button"}).appendTo(this.automation_save_button).click(function() {
				if (that.ref_enable_button.hasClass('enable') && that.soundplayer_container.data('soundplayer').buffer) {
					if (that.automation_save_diod.hasClass('warning')) {
						clearTimeout(that.automation_save_timeout);
						that.automation_save_diod.removeClass('warning');
						that.automation_save_diod.addClass('active');
						that._automation_record('save');
						setTimeout(function() {
							that.automation_save_diod.removeClass('active');
						},500);
					}
					else {
						that.automation_save_diod.addClass('warning');
						that.automation_save_timeout=setTimeout(function() {
							that.automation_save_diod.removeClass('warning');
						},5000);
					}
				}
			});
			this.automation_save_diod=$('<div/>', {'class':"auto_diod_blue"}).appendTo(this.automation_save_button);
//			$('<span/>', {'class':"button_text"}).html('2 press to save').appendTo(this.automation_save_button);
			
			// Fluo buttons
			this.fluo_buttons_container=$('<div/>', {id:'fluo_buttons_container'}).appendTo(this.controls.right_side_conteneur);
			that.previous_FX_button=$('<div/>', {id:'previous_effect_button'}).appendTo(this.fluo_buttons_container).click(function(){
				if (that.ref_enable_button.hasClass('enable') && $(this).hasClass('ON')) {
					that.next_FX_button.addClass('ON');
					if (that.controls.remote_active===true) {
						that._send_remote_event('fluo_previous');
					}
					else {
						that.element.trigger('send_fluoPrevious');
					}
				}
			});
			that.next_FX_button=$('<div/>',{id:'next_effect_button', 'class':'ON'}).appendTo(this.fluo_buttons_container).click(function(){
				if (that.ref_enable_button.hasClass('enable') && $(this).hasClass('ON')) {
					that.previous_FX_button.addClass('ON');
					if (that.controls.remote_active===true) {
						that._send_remote_event('fluo_next');
						}
					else {
						that.element.trigger('send_fluoNext');
						}	
				}
			});
			that.controls.buttons['fluo_previous']=that.previous_FX_button;
			that.controls.buttons['fluo_next']=that.next_FX_button;
			
			// Init Inter-Window communication and associated events
			function listen_to_main_window(message) {
				
				// same_origin
				var authorized_domain='http://'+window.location.host;
				
				that.originWindow=message.source;
				if (message.origin==authorized_domain && message.data=='ping') {
					console.log('ping');
					that.originWindow.postMessage({'pong':true},'*');
					$('#link_light').addClass('active');
					
					// SendUpdate event listener triggered on slider change 
					that.element.on('sendUpdate',function() {
						// if "Power button" is ON
						if (that.ref_enable_button.hasClass('enable')) {
							that.originWindow.postMessage({'update':true},'*');
						}
					});

					//Send light_off message
					that.element.on('send_fluoOff',function() {
						that.originWindow.postMessage({'fluoOff':true},'*');
					});
					that.element.on('send_fluoOn',function() {
						that.originWindow.postMessage({'fluoOn':true},'*');
					});
					
					//Send light_off message
					that.element.on('send_fluoNext',function() {
						that.originWindow.postMessage({'fluo_next':true},'*');
					});
					that.element.on('send_fluoPrevious',function() {
						that.originWindow.postMessage({'fluo_previous':true},'*');
					});
					
					// Send special_auto_mode update
					that.element.on('send_special_auto',function() {
						that.originWindow.postMessage({'special_auto_mode':true},'*');
					});
				}
				if (message.origin==authorized_domain && message.data=='wait') {
					console.log('wait');
					that.controls.faders_inhibit=true;
					that.interval_OFF=setInterval(function() {
						that.next_FX_button.removeClass('ON');
						that.previous_FX_button.removeClass('ON');
					},512);
					setTimeout(function() {
						that.interval_ON=setInterval(function() {
							that.next_FX_button.addClass('ON');
							that.previous_FX_button.addClass('ON');
						},512);
					},255);
				}
				if (message.origin==authorized_domain && message.data=='go') {
					console.log('go');
					that.controls.faders_inhibit=false;
					clearInterval(that.interval_OFF);
					clearInterval(that.interval_ON);
					setTimeout(function() {
						that.next_FX_button.addClass('ON');
						that.previous_FX_button.addClass('ON');
					},127);
				}
				if (message.origin==authorized_domain && message.data=='fluo_effect_goal') {
					console.log('FX_goal');
					setTimeout(function() {
						that.next_FX_button.removeClass('ON');
					},127);
				}
				if (message.origin==authorized_domain && message.data=='fluo_effect_first') {
					setTimeout(function() {
						that.previous_FX_button.removeClass('ON');
					},127);
				}
			}
			window.addEventListener('message',listen_to_main_window);
			
			// Init Internet RealTime communication
			$('#link_light').click(function(){
				console.log('remote');
				if (!$(this).hasClass('remote')) {
					$(this).addClass('remote');
					that.controls.remote_active=true;
					that._init_remote_event();
				}
				else {
					$(this).removeClass('remote');
					delete that.myFirebaseRef;
					that.controls.remote_active=false;
				}
			});
		},
		_construct_midi_mapper:function() {
			var that=this;
			that.note_map=[45,46,47,24];
			that.CC_map=[21,22,23,24,116,117];
			that.midi_mapper_container=$('<div/>', {'class':'MIDI_mapper'}).appendTo(that.controls.right_side_conteneur);
			$('<div/>').text('MIDI MAP : ').appendTo(that.midi_mapper_container);
			$('<span/>').text('Notes : ').appendTo(that.midi_mapper_container);
			$.each(that.note_map, function(index, value) {
				$('<input/>', {'class':'MIDI_map', size:'1', name:'note_map'+index}).val(value).appendTo(that.midi_mapper_container);
			});
			$('<br/>').appendTo(that.midi_mapper_container);
			$('<span/>').text('- CC - :').appendTo(that.midi_mapper_container);
			$.each(that.CC_map, function(index, value) {
				$('<input/>', {'class':'MIDI_map', size:'1', name:'CC_map'+index}).val(value).appendTo(that.midi_mapper_container);
				if (index==3) {
					$('<br/>').appendTo(that.midi_mapper_container);
				}
			});
			that.MIDI_debug_button=$('<div/>', {'class':'MIDI_map_button'}).text('DEBUG MODE ').appendTo(that.midi_mapper_container);
			that.MIDI_TAKE_button=$('<div/>', {'class':'MIDI_map_button'}).text('TAKE').appendTo(that.midi_mapper_container);
			that.MIDI_debug_button.click(function() {
				if (that.controls.MIDI_DEBUG_mode===false) {
					that.controls.MIDI_DEBUG_mode=true;
					$(this).addClass('active');
				}
				else {
					that.controls.MIDI_DEBUG_mode=false;
					$(this).removeClass('active');
				}
			});
			that.MIDI_TAKE_button.click(function() {
				that.note_map=new Array;
				that.CC_map=new Array;
				$('input.MIDI_map').each(function() {
					if ($(this).attr('name').substr(0,4)=='note') {
						that.note_map.push($(this).val());
					}
					else if ($(this).attr('name').substr(0,2)=='CC') {
						that.CC_map.push($(this).val());
					}
				});
				that._map_MIDI();
			});
		},
		_construct_full_mixer_button:function() {
			var that=this;
			this.fullmixer_button=$('#full_mixer_button').click(function() {
				if (!$(this).hasClass('active')) {
					$(this).addClass('active');
					that.show_full_mixer();
				}
				else {
					$(this).removeClass('active');
					that.hide_full_mixer();
				}
			});
			
			if (this.controls.full_mixer===false) {
				this.hide_full_mixer();
			}
		},
		show_full_mixer:function() {
			this.controls.conteneur.css({'height':'734px'});
			this.element.addClass('maxi');
			window.resizeTo(window.outerWidth,844);
			this.button_container.show();
			this.controler_container.show();
			this.midi_enable_button.show();
			this.soundplayer_container.show();
			this.automation_zone.show();
		},
		hide_full_mixer:function() {
			this.controls.conteneur.css('height','440px');
			this.element.removeClass('maxi');
			window.resizeTo(window.outerWidth,551);
			this.button_container.hide();
			this.controler_container.hide();
			this.midi_enable_button.hide();
			this.midi_mapper_container.hide();
			this.soundplayer_container.hide();
			this.automation_zone.hide();
		},
		_affect_faders: function () {
			var that=this;
			// Get the first property
			var versions_handlers=getFirstprop(that.versions);
			$.each(versions_handlers.MAIN, function(index,version) {
//				if (handler.size() && that.controls.sliders[version]) {
				if (that.controls.sliders[version]) {
					that.controls.sliders[version].slider({
						orientation: "vertical",
						animate: "fast",
						range: "min",
						min:1,
						max:123,
						value:1,
						step:2,
						change: function( event, ui ) {
							$( "#amount" ).val( ui.value );
							that._compute_mix();
							if (that.controls.faders_inhibit!==true) {
								if (that.controls.remote_active===true) {
									that._send_remote_event('update');
									}
								else {
									that._store_values();
									that.element.trigger('sendUpdate');
									}
							}
							},
						slide: function( event, ui ) {
							$( "#amount" ).val( ui.value );
							that._compute_mix();
							if (that.controls.remote_active===true) {
								that._send_remote_event('update');
								}
							else {
								that._store_values();
								that.element.trigger('sendUpdate');
								}
							}
						}
					);
				}
			});
			// Affect buttons
			this.ref_enable_button.click(function () {
				if (!that.ref_enable_button.hasClass('enable')) {
					that.ref_enable_button.addClass('enable');
					that.midi_enable_button.addClass('active');
					
//					that.controls.conteneur.animate({opacity:.7});
//					for(var key in that.controls.sliders) {
//						that.controls.sliders[key].slider("value",1);
//					}
					
					// Wait for the light to falloff in the room, before computing fader rest-state
					// Just for fun and for the attentive eye, we add a jerky chaser effect
					
					// We first test the green fader, cause sometimes there is only one fader and green is the more likely to be absent
					if (that.controls.value_tester['version_vert']) {
						setTimeout(function() {
							var chase=0;
							setTimeout(function() {
								that.controls.value_tester['version_fluo1'].css('opacity',1);
							},chase);
							chase=chase+150;
							setTimeout(function() {
								that.controls.value_tester['version_rouge'].css('opacity',1);
							},chase);
							chase=chase+150;
							setTimeout(function() {
								that.controls.value_tester['version_or'].css('opacity',1);
							},chase);
							chase=chase+150;
							setTimeout(function() {
								that.controls.value_tester['version_vert'].css('opacity',1);
							},chase);
						},100);
						setTimeout(function() {
							for(var key in that.controls.value_tester) {
								that.controls.value_tester[key].css('opacity',0);
							}
						},700);
						setTimeout(function() {
							for(var key in that.controls.value_tester) {
								that.controls.value_tester[key].css('opacity',1);
							}
						},800);
						setTimeout(function() {
							for(var key in that.controls.value_tester) {
								that.controls.value_tester[key].css('opacity',0);
							}
						},900);
					}
					
					// Sliders Up trigger update message
					setTimeout(function() {
						var first='not';		// JS object : Order of object properties may change (apparently not in this case) => to be monitored
						for(var key in that.controls.sliders) {
							if (first=='not') {
								that.controls.sliders[key].slider("value",77);
								first='yet';
							}
							else {
								that.controls.sliders[key].slider("value",12)
							}
						}
					},1001);

//					$.each(that.controls.sliders, function(version,slider) {
//						that.controls.fader_override_buttons[version].addClass('on');
//					});
					that.soundplayer_container.addClass('active').trigger('cssClassChanged');
					
					that.element.trigger('send_fluoOn');
					if (that.controls.remote_active===true) {
						that._send_remote_event('fluoOn');
					}
				}
				else  {
					that.element.trigger('send_fluoOff');
					if (that.controls.remote_active===true) {
						that._send_remote_event('fluoOff');
					}
					
					// Sliders first to to update before quit
					setTimeout(function() {
						for(var key in that.controls.sliders) {
							that.controls.sliders[key].slider("value",12);
							that.controls.value_tester[key].css('opacity',0);
						}
					},1001);
					
					that.ref_enable_button.removeClass('enable');
					that.midi_enable_button.removeClass('active').removeClass('enable');
					
					// MIDI init is a bit unclear
					if (typeof that.midi_device_container.texte !=='undefined') {
						that.midi_device_container.texte.fadeOut(500);
					}

					$.each(that.controls.sliders, function(version,slider) {
						that.controls.fader_override_buttons[version].removeClass('on');
					});
					that.soundplayer_container.removeClass('active').trigger('cssClassChanged');
					that.automation_diod.removeClass('active');
					that.controls.record_on=false;
				}
			});
			
			// MIDI enable button
			setTimeout(function() {
				that.midi_enable_button.click(function () {
					if ($(this).hasClass('active')) {
						$(this).toggleClass('enable');
						if ($(this).hasClass('enable')) {
							// Re-open in case of device plugged after page load
							that.controls.midi_device.name=that.Jazz.MidiInOpen(0);
							that.midi_device_container.texte=$('<div/>').text(that.controls.midi_device.name.slice(0,9)).prependTo(that.midi_device_container).hide();
							that.midi_device_container.texte.fadeIn(180);
							that.midi_mapper_container.show();
							that._map_MIDI();
							that.midi_refresh=window.setInterval(function() {
								var message;
								while(message=that.Jazz.QueryMidiIn()) {
									var timestamp=message[0];
									var msg_type=message[1];
									var key_nbr=message[2];
									var msg_value=message[3];
									if (that.controls.MIDI_DEBUG_mode===true) {
										console.log('MIDI message (Time,Type,Nbr,Val/Vel) : '+message);
									}
									// 128=>NoteOff 144=>NoteOn 176<>191=>ControlChange (channel 1-16)
									if ( msg_type==128 || msg_type==144 || (msg_type>=176 && msg_type<=191)) {
										that._interpret_MIDI(msg_type, key_nbr, msg_value)
									}
								}
							},51);
						}
						else {
							window.clearInterval(that.midi_refresh);
							that.midi_device_container.texte.fadeOut(357);
							that.midi_mapper_container.hide();
						}
					}
				});
			},700);

			// Affect Key Events
			var lastEvent;
			$(document).bind('keydown keyup', function(e){
				if (lastEvent && lastEvent.which == e.which && lastEvent.type==e.type) {
			        return;
			    }
				$.each(that.controls.key_overrides,function(version,keystroke) {
					if (e.which==keystroke) {
						
						if(!that.controls.sliders_override_values[version]) {
//							console.log('down');
							lastEvent = e;
							that.controls.sliders_override_values[version]='override';
							that.controls.fader_override_buttons[version].addClass('on');
						}
						else {
//							console.log('up');
							lastEvent = null;
							that.controls.sliders_override_values[version]=null;
							that.controls.fader_override_buttons[version].removeClass('on');
//							that.controls.fader_control_buttons[version].removeClass('on');
						}
//						$.each(that.controls.fader_control_buttons,function(version_control,dom) {
//							if(version_control==version) {
//								$(this).addClass('on');
//							}
//							else {
//								$(this).removeClass('on');
//							}
//						});
						that._compute_mix();
						that._store_values();
						that.element.trigger('sendUpdate');
					}
				});
			});
			// Affect XY Controls
			this.xy_controler.click(function () {
				if (!$(this).hasClass('on') && that.ref_enable_button.hasClass('enable')) {
					$(this).addClass('on');
				}
				else {
					$(this).removeClass('on');
				}
			});
			this.xy_controler.mousemove(function(e) {
				if ($(this).hasClass('on')) {
					var x=Math.round((e.pageX - $(this).offset().left - $(this).width()/2)*120/($(this).width()/2));
					var y=Math.round(-(e.pageY - $(this).offset().top - $(this).height()/2)*120/($(this).height()/2));
	//				that.test_coordinates.text(x+' / '+y);
					var algorythm={1:x,2:y,3:-x,4:-y};
					i=1;
					$.each(that.controls.sliders, function(version,slider_wdgt) {
						if (i==1) {
							if (algorythm[i]<20) {
								algorythm[i]=20;
							}
							slider_wdgt.slider("value",algorythm[i]+50);
						}
						else {
							slider_wdgt.slider("value",algorythm[i]);
						}
						i++;
					});
					that._compute_mix();
				}
			});
		},
		_map_MIDI:function() {
			var that=this;
			that.MIDI_map={};
			that.MIDI_map.Notes={};
			that.MIDI_map.Notes.On={};
			that.MIDI_map.Notes.Off={};
			that.MIDI_map.CC={};
			// Versions names are dynamical
			var versions=getFirstprop(that.versions);
			$.each(versions.MAIN, function (index, version) {
				// Same rule as when we construct the mixer
				if (isNaN(parseInt(version.substr(-1))) || version.substr(-1)=='1') {
					that.MIDI_map.Notes.On[that.note_map[0]]=version;
					that.MIDI_map.Notes.Off[that.note_map[0]]=version;
					that.MIDI_map.CC[that.CC_map[0]]=version;
					that.note_map.shift();
					that.CC_map.shift();
				}
			});
			that.MIDI_map.CC[that.CC_map[0]]='fluo_previous';
			that.MIDI_map.CC[that.CC_map[1]]='fluo_next';
		},
		_interpret_MIDI:function (msg_type, key_nbr, msg_value){
			var that=this;
			if (msg_type==144) {
				if (version=that.MIDI_map.Notes.On[key_nbr]) {
//					if (that.controls.sliders_override_values[version]) {
//						delete that.controls.sliders_override_values[version];
//						that.controls.fader_override_buttons[version].removeClass('on');
//					}
//					else {
						that.controls.sliders_override_values[version]=true;
						that.controls.fader_override_buttons[version].addClass('on');
//					}
				}
			}
			else if (msg_type==128) {
				if (version=that.MIDI_map.Notes.Off[key_nbr]) {
					delete that.controls.sliders_override_values[version];
					that.controls.fader_override_buttons[version].removeClass('on');
				}
			}
			else if (msg_type>=176 && msg_type<=191) {
				if (version=that.MIDI_map.CC[key_nbr]) {
					if (that.controls.sliders[version]) {
						that.controls.sliders[version].slider('value',msg_value);
					}
					else if (that.controls.buttons[version]) {
						that.controls.buttons[version].click();
					}
				}
			}
			that._compute_mix();
			that._store_values();
			that.element.trigger('sendUpdate');
		},
		_compute_mix:function() {
			var that=this;
			if (typeof that.ref_enable_button!=='undefined') {
				if (that.ref_enable_button.hasClass('enable')) {
					// cette fonction calcule les valeurs combinées des sliders et les affecte dans {controls}
					this._compute_slider_real_value();
					// la fonction qui récupére les valeurs d'opacity dans {controls} et les applique réellement aux images versionnées a été déplacée dans la page cible
				}
			}
		},
		_compute_slider_real_value: function() {
			// sum values
			var sum=0;
			var that=this;
			var other_sliders=Object.keys(that.controls.sliders).length-1;
			$.each(this.controls.sliders, function(version,slider_wdgt) {
				// Detect override button
				if (!that.controls.sliders_override_values[version]) {
					sum+=slider_wdgt.slider("option","value"); 
				}
				else {
					sum+=123;
				}
			});
			var i=1;
			var moyenne;
			$.each(this.controls.sliders, function(version,slider_wdgt) {
				// Detect override button
				if (!that.controls.sliders_override_values[version]) {
					var s_value=slider_wdgt.slider("option","value");
				}
				else {
					var s_value=123;
				}
				
				// substract current to compute mean of other sliders
				if (other_sliders) {
					moyenne=(sum-s_value)/other_sliders;
				}
				else {
					moyenne=0;
				}
				// Constante profondeur : - "i" is integrated : images at the bottom of the stack are less altered by this constant
				// 						  - mean is integrated : if there is only a few faders up, the constant is weeker
				var constante_profondeur=((moyenne-12)*77)/(i*44);
				i++;

				// substract mean of other sliders to avoid front image masking images in the background
				that.controls.sliders_real_values[version]=Math.round((s_value-(Math.pow(moyenne,3)/50000))-(constante_profondeur*other_sliders)/3);
				
				// value_tester = fader test light
				that.controls.value_tester[version].css('opacity',s_value/120);
				//that.controls.value_tester[version].text(that.controls.sliders_real_values[version].toString().substr(0,4));
			});
			
			// Cas particulier de l'image blanche
			that.controls.sliders_real_values['white']=moyenne;
		},
		_store_values:function() {
			var that=this;
			localStorage.setItem('sliders_real_values',JSON.stringify(that.controls.sliders_real_values));
		},
		_automation_record:function(action) {
			this.audioPosition=this.soundplayer_container.data('soundplayer').currentPosition;
			function step() {
				
			}
			if (action=='start') {
				
			}
		},
		_init_remote_event:function() {
		    this.myFirebaseRef = new Firebase("https://blazing-inferno-7181.firebaseio.com/");
		},
		_send_remote_event:function(event) {
			var that=this;
			if (event=='update') {
				var slider_data = this.myFirebaseRef.child("slider_data");
				slider_data.set(JSON.stringify(that.controls.sliders_real_values), function(error) {
					if (error) {
						console.log('Firebase error : '+error);
					}
				});
			}
			else if (event=='fluoOff') {
				var light_state = this.myFirebaseRef.child("light_state");
				light_state.set({fluoOff:true}, function(error) {
					if (error) {
						console.log('Firebase error : '+error);
					}
				});
			}
			else if (event=='fluoOn') {
				var light_state = this.myFirebaseRef.child("light_state");
				light_state.set({fluoOn:true}, function(error) {
					if (error) {
						console.log('Firebase error : '+error);
					}
				});
			}
		}
		}
	);
});