var l = {
        v: function(t) {
            return t.split("").reverse().join("")
        },
        r: function(t, e) {
            t = t.split("");
            for (var i, o = r + r, a = t.length; a--; )
                i = o.indexOf(t[a]),
                ~i && (t[a] = o.substr(i - e, 1));
            return t.join("")
        },
        s: function(t, e) {
            var i = t.length;
            if (i) {
                var o = s(t, e)
                  , a = 0;
                for (t = t.split(""); ++a < i; )
                    t[a] = t.splice(o[i - 1 - a], 1, t[a])[0];
                t = t.join("")
            }
            return t
        },
        i: function(t, e) {
            return l.s(t, e ^ id)
        },
        x: function(t, e) {
            var i = [];
            return e = e.charCodeAt(0),
            each(t.split(""), function(t, o) {
                i.push(String.fromCharCode(o.charCodeAt(0) ^ e))
            }),
            i.join("")
        }
}
function ShitConvert(t) {
        if (t.indexOf("audio_api_unavailable")) {
            var e = t.split("?extra=")[1].split("#")
              , o = "" === e[1] ? "" : a(e[1]);
            if (e = a(e[0]),
            "string" != typeof o || !e)
                return t;
            o = o ? o.split(String.fromCharCode(9)) : []; console.log(o);
            for (var s, r, n = o.length; n--; ) {
                if (r = o[n].split(String.fromCharCode(11)),
                s = r.splice(0, 1, e)[0],
                !l[s])
                    return t;
                e = l[s].apply(null, r)
            }
            if (e && "http" === e.substr(0, 4))
                return e
        }
        return t
}
function a(t) {
    var r = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMN0PQRSTUVWXYZO123456789+/=";
        if (!t || t.length % 4 == 1)
            return !1;
        for (var e, i, o = 0, a = 0, s = ""; i = t.charAt(a++); )
            i = r.indexOf(i),
            ~i && (e = o % 4 ? 64 * e + i : i,
            o++ % 4) && (s += String.fromCharCode(255 & e >> (-2 * o & 6)));
        return s
}
function s(t, e) {
        var i = t.length
          , o = [];
        if (i) {
            var a = i;
            for (e = Math.abs(e); a--; )
                e = (i * (a + 1) ^ e + a) % i,
                o[a] = e
        }
        return o
}

//////////////////////////

var player, playPromise, gTimer, aid;
function play(url, item){
	if (playPromise) {
		playPromise.then(_ => {
			realPlay(url, item);
			playPromise = 0;
		})
  } else {
	realPlay(url, item);
  }
}
function realPlay(url, item){
	if(item.getAttribute('data-playing') == 1){
		player.pause();
		item.setAttribute('style', '');
		item.setAttribute('data-playing', '2');
		pauseTimer(item);
	} else {
	    if(item.getAttribute('data-playing') == 2){
    		player.play();
			resumeTimer(item);
    	} else {
			if(player){
				player.pause();
			}
			prev = document.querySelectorAll('[data-playing="1"]')[0];
			if(prev){
			    prev.setAttribute('style', '');
				prev.setAttribute('data-playing', '0');
				removeTimer(prev);
			}
			prev = document.querySelectorAll('[data-playing="2"]')[0];
			if(prev){
				prev.setAttribute('data-playing', '0');
				removeTimer(prev);
			}
			var realurl = ShitConvert(url);
    	    		player = new Audio(realurl);
			aid = item.getAttribute('data-aid');
			player.addEventListener("ended", function(){
				let next = document.querySelectorAll('[data-aid="'+(parseInt(aid)+1)+'"]')[0];
				if(next){
					next.click();
				} else {
					document.querySelectorAll('[data-aid="1"]')[0].click();
				}
			});
    	    	playPromise = player.play();
			startTimer(item);
		}
		item.setAttribute('style', 'background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Ccircle%20cx%3D%2212%22%20cy%3D%2212%22%20r%3D%2212%22%20fill%3D%22%235181B8%22%2F%3E%3Cpath%20fill%3D%22%23FFF%22%20d%3D%22M8%207.596c0-.33.277-.596.607-.596h1.786c.335%200%20.607.267.607.596v8.808a.605.605%200%200%201-.607.596H8.607A.602.602%200%200%201%208%2016.404V7.596zm5%200c0-.33.277-.596.607-.596h1.786c.335%200%20.607.267.607.596v8.808a.605.605%200%200%201-.607.596h-1.786a.602.602%200%200%201-.607-.596V7.596z%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");');
		item.setAttribute('data-playing', '1');
	}
}

function startTimer(item){
	let pl = item.parentElement.getElementsByClassName('audio_row__duration')[0],
	    dur = item.getAttribute('data-duration');
	$("#audio"+aid).slider({
            orientation: "horizontal",
            range: "min",
            max: dur,
            change: refreshSlider
   	});
	gTimer = {
		place: pl,
	        maxTime: pl.innerHTML,
		duration: dur,
	        timerInterval: setInterval(function(){processTimer();}, 1000)
	};
}
function removeTimer(item){
        clearInterval(gTimer.timerInterval);
	let pl = item.parentElement.getElementsByClassName('audio_row__duration')[0];
	$("#audio"+aid).slider("destroy");
	pl.innerHTML = gTimer.maxTime;
}
function pauseTimer(){
	clearInterval(gTimer.timerInterval);
}
function resumeTimer(){
	gTimer.timerInterval = setInterval(function(){processTimer();}, 1000);
}

function processTimer(){
	let time = player.currentTime;
	let sec = Math.floor(time % 60),
	    min = Math.floor(time / 60),
		percent = Math.floor((time / gTimer.duration) * 100);
	$("#audio"+aid).find(".ui-slider-handle")[0].style = 'left: ' + percent + '%;';
	$("#audio"+aid).find(".ui-slider-range")[0].style = 'width: ' + percent + '%;';
	if(sec < 10){
		sec = "0" + sec;
	}
	gTimer.place.innerHTML = min + ':' + sec + '/' + gTimer.maxTime;
	
}
function refreshSlider(){
	player.currentTime = $("#audio"+aid).slider("value");
}
/////////////////////

function nextPage(){
	var sid = document.getElementsByName('start')[0];
        sid.value = parseInt(sid.value) + 90;
	document.getElementById('shorten_btn').click();
}
function prevPage(){
	var sid = document.getElementsByName('start')[0];
        sid.value = parseInt(sid.value) - 90;
	document.getElementById('shorten_btn').click();
}

///////////////////////

function download(url){
    var realurl = ShitConvert(url);
    var link = document.createElement("a");
    link.download = true;
    link.href = realurl;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
