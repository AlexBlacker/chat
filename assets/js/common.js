$(document).ready(function () {
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		} else {
			var currentPermission;
			Notification.requestPermission( function(result) { currentPermission = result  } );
		}
		
        var socket = io.connect('http://chat.svcontact.ru:8008');
        var name = 'Пётр_' + (Math.round(Math.random() * 10000));
        var messages = $("#messages");
        var message_txt = $("#message_text");
        var smilelistmarg = $(window).width()/10;
        var smilelistwidth = smilelistmarg*9;
        $(".smilelist").css('width', smilelistwidth-10+'px');
        $(".smilelist").css('left', '-10px');
        if (messages.length) {
	    	$("html, body").scrollTop(messages[0].scrollHeight);
	    }
        
        if ($("#password").length) {
	    	$("#password").focus();
	    }
        
        $('.chat .nick').text(name);
        
        setInterval(function(){
        	$.ajax({
				type: "POST",
				url: "/home/updonline",
				data: {},
				cache: false,
				dataType: 'json',
				success: function(json){
					$("#online").html(json);
				}
			});
        },3000);
        // setInterval(function(){
        	// $("#messages").load( document.location + ' #messages > *', function(){
        		// $.each($('.direct-chat-msg'), function(k, e){
					// if ($(e).hasClass('same')) {
						// var m = $(e).find('.direct-chat-text').html();
						// $(e).prev('.direct-chat-msg').find('.direct-chat-text').append(m);
						// $(e).remove();
					// }
				// });
        	// });
        // },20000);

        function msg(to, json) {
        	var m = '';
        	if ($('#myid').val()==to) {
        		whose = 'right'; pul1='pull-right'; pul2='pull-left';
        	} else {
        		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				} else {
	        		var mailNotification = new Notification(json.name, {
					    tag : "ache-mail",
					    body : json.mess,
					    icon : json.avatar,
					    sound: '/assets/message.mp3'
					});
				}
        		whose = ''; pul1='pull-left'; pul2='pull-right';
        	}
        	var same = '';
        	var sameminute = '';
        	if (json.same==1) {
        		same = 'same';
        	}
        	if (json.sameminute==1) {
        		sameminute = 'same_date';
        	}
        	m += '<div class="direct-chat-msg new '+same+' '+whose+'">';
		    m += '<div class="direct-chat-info clearfix">';
		    if (json.same!=1) {
		    	m += '<span class="direct-chat-name '+pul1+'">'+json.name+'</span>';
		    	//m += '<span class="direct-chat-timestamp '+pul2+'">'+json.date+'</span>';
		    }
		    m += '</div>';
		    m += '<img class="direct-chat-img" src="'+json.avatar+'" alt="message user image">';
		    m += '<div class="direct-chat-text">';
		    m += '<div class="direct-chat-timestamp '+sameminute+'">'+json.date+'</div>';
		    m += '<p>'+json.mess+'</p>';
		    
		    if (json.attach) {
		    	m += '<div class="attach">';
        		m += '<a href="/uploads/'+json.attach+'"><img src="/uploads/'+json.attach+'" alt="'+json.name+'" /></a>';
        		m += '<div class="atach_open" rel="/uploads/'+json.attach+'">Открыть</div>';
        		m += '</div>';
		    }
		    
		    
		    
		    m += '<div style="clear: both"></div></div></div>';
        	$(".nomess").hide('fade', 500, function(){
        		$(".nomess").remove();
        	});
            messages.append(m).animate({scrollTop: messages[0].scrollHeight}, 500);
			//.scrollTop(messages[0].scrollHeight);
            read();
        }

        function msg_system(message) {
            var m = '<div class="msg system">' + safe(message) + '</div>';
            messages.append(m).scrollTop(messages[0].scrollHeight);
            $("html, body").scrollTop(messages[0].scrollHeight);
        }
        function type(name) {
        	$(".im_typing").html(' пишет');
	    	$(".im_typing_wrap").show('fade', 500);
	    	
	    }

        socket.on('type', function (data) {
			if(data.userid==$('#myid').val()&&data.myid==$('#userid').val()) {
	        	type(data.name);
	        	setTimeout(function(){
	        		$(".im_typing_wrap").hide('fade', 500).html();
	        	},3000);
	        }
	        if (messages.length) {
		    	$("html, body").scrollTop(messages[0].scrollHeight);
		    }
		    read();
	    });
	    
        socket.on('read', function () {
            //msg_system('Соединение установленно!');
        });

        socket.on('message', function (data) {
        	$(".im_typing_wrap").hide('fade', 300).html();
            msg(data.user, data);
            message_txt.focus();
		    $("html, body").animate({scrollTop: messages[0].scrollHeight}, 500, function(){
		    	$.each($('.direct-chat-msg'), function(k, e){
					if ($(e).hasClass('same')) {
						var m = $(e).find('.direct-chat-text').html();
						$(e).prev('.direct-chat-msg').find('.direct-chat-text').append(m);
						$(e).remove();
					}
				});
		    });
		    read();
        });

        $(document).on('click', '#message_btn', function () {
            var text = $("#message_text").val();
            var to = $('#userid').val();
            var attach = $('#file').val();
            if (text.length <= 0) {return;}
            message_txt.val("");
            $("#file").val("");
            $('.im_editable').text('');
			$(".attach_wrap .attach").hide('fade', 200, function(){
				$(".attach_wrap .remove").removeAttr('id');
				$(".attach_wrap .attach").html('');
				$(".attach_wrap .remove").hide('fade', 200, function(){
					$(".attach_wrap .add").show('fade', 200);
					document.getElementById("attach").value = "";
				});
			});
            $.ajax({
				type: "POST",
				url: "/home/send",
				data: 'to='+to+'&mess='+text+'&attach='+attach,
				cache: false,
				dataType: 'json',
				success: function(json){
					socket.emit("message", json);
					$("#messtext").focus();
				    $("html, body").animate({scrollTop: messages[0].scrollHeight}, 500);
				    $("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
				    $(".box-body").css('padding-bottom', ($(".box-footer").height()+60)+'px');
				    
				    document.getElementById("messages").addEventListener('click',function() {
				    	var ontop = 0 - messages[0].scrollHeight;
				        document.getElementById("body").style.top = ontop;//Scroll by 120px
				    });
				    
				}
			});
        });
        
        if ($(".onlines").length) {
        	setInterval(function(){
        		$(".onlines").load( document.location + ' .onlines > *');
        		
        		if ($(".yes").length) {
        			if (!$(".sended").length) {
        				var mailNotification = new Notification('Кто-то зашел в чатик', {
						    tag : "ache-mail",
						    body : $(".yes").html(), //'Кто-то зашел в чат',
						    icon : '/assets/img/logo.png',
						    sound: '/assets/message.mp3'
						});
						$(".onlines").addClass('sended');
        			}
        		} else {
        			$(".onlines").removeClass('sended');
        		}
        	}, 3000);
        	
        }
        
        if ($("#messtext").length) {
	        document.getElementById("messtext").addEventListener("input", function(e) {
	        	$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
	        	$("html, body").scrollTop(messages[0].scrollHeight);
	        	message_txt.val($("#messtext").text());
			}, false);
	        
	        document.getElementById("messtext").addEventListener("focus", function(e) {
	        	$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
	        	$("html, body").scrollTop(messages[0].scrollHeight);
	        	message_txt.val($("#messtext").text());
			}, false);
        }
        $(document).on('keypress', '.im_editable', function () {
        	document.getElementById("messtext").addEventListener("input", function(e) {
        		$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
        		$("html, body").scrollTop(messages[0].scrollHeight);
	        	message_txt.val($("#messtext").text());
			}, false);
        });
        $(document).on('keypress', '.im_editable', function () {
        	$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
        	$("html, body").scrollTop(messages[0].scrollHeight);
        	document.getElementById("messages").addEventListener('click',function() {
				var ontop = 0 - messages[0].scrollHeight;
				document.getElementById("body").style.top = ontop;//Scroll by 120px
			});
        });
        $(document).on('focus', '.im_editable', function () {
        	//alert()
        	$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
        	$("html, body").scrollTop(messages[0].scrollHeight);
        	document.getElementById("messages").addEventListener('click',function() {
				var ontop = 0 - messages[0].scrollHeight;
				document.getElementById("body").style.top = ontop;//Scroll by 120px
			});
			setTimeout(function(){
				$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
	        	$("html, body").scrollTop(messages[0].scrollHeight);
	        	document.getElementById("messages").addEventListener('click',function() {
					var ontop = 0 - messages[0].scrollHeight;
					document.getElementById("body").style.top = ontop;//Scroll by 120px
				});
				
				setTimeout(function(){
					$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
		        	$("html, body").scrollTop(messages[0].scrollHeight);
		        	document.getElementById("messages").addEventListener('click',function() {
						var ontop = 0 - messages[0].scrollHeight;
						document.getElementById("body").style.top = ontop;//Scroll by 120px
					});
				},1000);
				
			},1000);
        });
        
        $(document).on('click', ".smilelist ul li a", function(){
        	var messtext = document.getElementById("messtext");
			var text = $("#messtext").text();
        	var smile = $.trim($(this).attr('href'));
        	var mess = text.splice(getCaretPosition(messtext), 0, smile);
        	$("#messtext").text(mess);
        	$("#message_text").val($.trim($("#messtext").text()));
        	setCaretPosition('messtext', parseInt(getCaretPosition(messtext))+parseInt(smile.length))
        	messtext.focus($("#messtext"));
        	$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
			$(".box-body").css('padding-bottom', ($(".box-footer").height()+60)+'px');
			$(".im_editable").scrollTop($(".im_editable").scrollHeight);
			$.ajax({
				type: "POST",
				url: "/home/smilestat",
				data: {smile:smile},
				cache: false,
				dataType: 'json',
				success: function(json){
					$(".smilelist").load( document.location + ' .smilelist > *');
					
				}
			});
        	return false;
        });
		$(document).on('click', ".smilebutton", function(){
			if ($(".smilelist").is(":visible")) {
				$(".smilelist").hide('fade', 300);
				$(".closesmiles").hide('fade', 300);
			} else {
				$(".smilelist").show('fade', 300);
				$(".closesmiles").show('fade', 300);
			}
			$("#messtext").focus();
			return false;
		});
		
		$(document).on('click', ".closesmiles", function(){
			$(".smilelist").hide('fade', 300);
			$(".closesmiles").hide('fade', 300);
			$("#messtext").focus();
			$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
			if (messages.length) {
		    	$("html, body").scrollTop(messages[0].scrollHeight);
		    }
		});
		
		$(document).on('click', ".attach_wrap .add", function(){
			$("#attach").click();
		});
		
		$(document).on('click', ".attach_wrap .remove", function(){
			var file = this.id;
			$.ajax({
				type: "POST",
				url: "/home/attachdel",
				data: {attach:file},
				cache: false,
				dataType: 'json',
				success: function(json){
					document.getElementById("attach").value = "";
					$(".attach_wrap .attach").hide('fade', 200, function(){
						$(".attach_wrap .remove").removeAttr('id');
						$(".attach_wrap .attach").html('');
						$(".attach_wrap .remove").hide('fade', 200, function(){
							$(".attach_wrap .add").show('fade', 200);
						});
					});
				}
			});
			return false;
		});
		
		$(document).on('change', "#attach", function(){
			var files = this.files;
			var fd = new FormData();
			fd.append('attach', files[0]);
			var xhr = new XMLHttpRequest();
			      
		    xhr.upload.addEventListener("progress", function(event){
		    	//uploadProgress
		    	var percent = Math.round(event.loaded * 100 / event.total);
		    	$(".attach_wrap .add").hide('fade', 100, function(){
		    		$(".loading").show('fade', 100, function(){
						$(".loading").text(percent+'%');
					});
		    	});
		    	
				if (percent==100) {
					$(".loading").hide('fade', 100);
				}
		    }, false);
		    xhr.addEventListener("load", function(event){
		    	//uploadComplete
		    	var res = event.target.responseText;
		    	res = jQuery.parseJSON(res),
		    	console.log(res);
		    	if (res.result) {
		    		$("#file").val(res.text);
		    		$(".attach_wrap .remove").show('fade', 200, function(){
		    			$(".attach_wrap .remove").attr('id',res.text);
			    		$(".attach_wrap .attach").html('<img src="/uploads/'+res.text+'" />');
			    		$(".attach_wrap .attach").show('fade', 200);
			    	});
		    	}
		    	
		    }, false);
		    xhr.addEventListener("error", function(event){
		    	//uploadFailed
		    	console.log("Upload failed.");
		    }, false);
		    xhr.addEventListener("abort", function(event){
		    	//uploadCanceled
		    	console.log("Upload canceled.");
		    }, false);
		    
		    xhr.open("POST", "/home/attach?r="+Math.random(), true);
		    xhr.send(fd);
			
		});
		
		$(document).on('click', ".attach a", function(){
			var img = $(this).attr('href');
			var html = '<div id="layer_bg" class="fixed"><img src="'+img+'"><div class="closeimg"></div></div>';
			$("body").append(html);
			$("#layer_bg").show('fade', 200);
			return false;
		});
		$(document).on('click', ".atach_open", function(){
			var img = $(this).attr('rel');
			var html = '<div id="layer_bg" class="fixed"><img src="'+img+'"><div class="closeimg"></div></div>';
			$("body").append(html);
			$("#layer_bg").show('fade', 200);
			return false;
		});
		
		$(document).on('click', ".closeimg", function(){
			$("#layer_bg").hide('fade', 200, function(){
				$("#layer_bg").remove();
			});
			return false;
		});
		
		$(document).on('keyup', '.im_editable', function(e) {
			message_txt.val($(this).text());
			//console.log($('.im_editable').height());
			$("#message_btn").css('line-height', ($('.im_editable').height()-2)+'px');
			$(".box-body").css('padding-bottom', ($(".box-footer").height()+60)+'px');
			$(".im_editable").scrollTop($(".im_editable").scrollHeight);
			//console.log(e.which);
		    if(e.which === 13) {
		    	if ($.trim(message_txt.val())!='') {
		    		$("#message_btn").click();
		    		return false;
		    	}
		    } else {
		    	var name = $('#name').val();
		    	var userid = $('#userid').val();
		        var myid = $('#myid').val();
		        $.ajax({
					type: "POST",
					url: "/home/updateonline",
					data: {},
					cache: false,
					dataType: 'json',
					success: function(json){
						socket.emit("type", {name: name, myid: myid, userid: userid,});
					}
				});
		    	
		    }
		    if (messages.length) {
		    	$("html, body").scrollTop(messages[0].scrollHeight);
		    }
			
		});
		
		$(document).on('click', ".load", function(){
			var $this = $(this).find('a');
			$('.direct-chat-msg').removeClass('new');
			var page = parseInt($this.attr('rel'));
			page = parseInt(page)+1;
			$this.attr('rel', page);
			$this.hide('fade', 200, function() {
				$(".load .loader").show('fade', 200);
				
				$.ajax({
					type: "POST",
					url: "/home/load/"+page,
					data: {},
					cache: false,
					dataType: 'json',
					success: function(json){
						if ($this.text()=='Чё было раньше') {
							$this.text('Ещё раньше');
						} else if ($this.text()=='Ещё раньше') {
							$this.text('И ещё раньше');
						} else if ($this.text()=='И ещё раньше') {
							$this.text('И ещё...');
						} else {
							$this.text('Это было очень давно');
						}
						
						$(".load .loader").hide('fade', 200, function() {
							$this.show('fade', 200);
							$("#messages").prepend(json);
							
							var scr = $('.direct-chat-msg.new').last().offset().top-36;
							$('html,body').scrollTop(scr); //.animate({scrollTop: scr}, 1);
							
							$.each($('.direct-chat-msg'), function(k, e){
								if ($(e).hasClass('same')) {
									var m = $(e).find('.direct-chat-text').html();
									$(e).prev('.direct-chat-msg').find('.direct-chat-text').append(m);
									$(e).remove();
								}
							});
							
						});
					}
				});
				
			});
			
			
			
			return false;
		});
		
		$.each($('.direct-chat-msg'), function(k, e){
			if ($(e).hasClass('same')) {
				var m = $(e).find('.direct-chat-text').html();
				$(e).prev('.direct-chat-msg').find('.direct-chat-text').append(m);
				$(e).remove();
			}
		});
		
		
		
        function safe(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }
        
        // $(document).on('keyup', message_txt, function(e) {
        	// //console.log(e.which);
		    // if(e.which === 13) {
		    	// if ($.trim(message_txt.val())!='') {
		    		// $("#message_btn").click();
		    		// return false;
		    	// }
		    // } else {
		    	// var name = $('#name').val();
		    	// var userid = $('#userid').val();
		        // var myid = $('#myid').val();
		        // $.ajax({
					// type: "POST",
					// url: "/home/updateonline",
					// data: {},
					// cache: false,
					// dataType: 'json',
					// success: function(json){
						// socket.emit("type", {name: name, myid: myid, userid: userid,});
					// }
				// });
// 		    	
		    // }
		    // if (messages.length) {
		    	// $("html, body").scrollTop(messages[0].scrollHeight);
		    // }
		// });
        
        function read() {
        	$.ajax({
				type: "POST",
				url: "/home/read",
				data: {},
				cache: false,
				dataType: 'json',
				success: function(json){
					//console.log(json);
				}
			});
        }
        
        function getInputSelection(el) {
		    var start = 0, end = 0, normalizedValue, range,
		        textInputRange, len, endRange;
		
		    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
		        start = el.selectionStart;
		        end = el.selectionEnd;
		    } else {
		        range = document.getSelection();
		
		        if (range && range.parentElement() == el) {
		            len = el.value.length;
		            normalizedValue = el.value.replace(/\r\n/g, "\n");
		
		            textInputRange = el.createTextRange();
		            textInputRange.moveToBookmark(range.getBookmark());
		            endRange = el.createTextRange();
		            endRange.collapse(false);
		
		            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
		                start = end = len;
		            } else {
		                start = -textInputRange.moveStart("character", -len);
		                start += normalizedValue.slice(0, start).split("\n").length - 1;
		
		                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
		                    end = len;
		                } else {
		                    end = -textInputRange.moveEnd("character", -len);
		                    end += normalizedValue.slice(0, end).split("\n").length - 1;
		                }
		            }
		        }
		    }
		
		    return {
		        start: start,
		        end: end
		    };
		}
        
        
        String.prototype.splice = function(idx, rem, str) {
		    return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
		};
        
        function getCaretPosition(editableDiv) {
		  var caretPos = 0,
		    sel, range;
		  if (window.getSelection) {
		    sel = window.getSelection();
		    if (sel.rangeCount) {
		      range = sel.getRangeAt(0);
		      if (range.commonAncestorContainer.parentNode == editableDiv) {
		        caretPos = range.endOffset;
		      }
		    }
		  } else if (document.selection && document.selection.createRange) {
		    range = document.selection.createRange();
		    if (range.parentElement() == editableDiv) {
		      var tempEl = document.createElement("span");
		      editableDiv.insertBefore(tempEl, editableDiv.firstChild);
		      var tempRange = range.duplicate();
		      tempRange.moveToElementText(tempEl);
		      tempRange.setEndPoint("EndToEnd", range);
		      caretPos = tempRange.text.length;
		    }
		  }
		  return caretPos;
		}
		
		function setCaretPosition(div, pos) {
			if (pos===undefined) {pos=0;}
		    var el = document.getElementById(div);
		    var range = document.createRange();
		    var sel = window.getSelection();
		    console.log(el.childNodes[0])
		    range.setStart(el.childNodes[0], el.childNodes[0].length);
		    range.collapse(true);
		    sel.removeAllRanges();
		    sel.addRange(range);
		    el.focus();
		}
        
    });