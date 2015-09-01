window.onload = function() {
    var oUl = document.getElementById("nav");
    var aLi = oUl.getElementsByTagName("li");
    var i = 0;
    for (i = 0; i < aLi.length; i++) {
        aLi[i].timer = null;
        aLi[i].speed = 0;
        aLi[i].onmouseover = function() {
            startMove(this, 250);
        };
        aLi[i].onmouseout = function() {
            startMove2(this, 100);
        };
    }
};

function startMove(obj, iTarget) {
    if (obj.timer) {
        clearInterval(obj.timer);
    }
    obj.timer = setInterval(function() {
        doMove(obj, iTarget);
    }, 30)
};

function doMove(obj, iTarget) {
    obj.speed += 3;
    if (Math.abs(iTarget - obj.offsetWidth) < 1 && Math.abs(obj.speed) < 1) {
        clearInterval(obj.timer);
        obj.timer = null;
    }
    else {
        if (obj.offsetWidth + obj.speed >= iTarget) {
            obj.speed *= -0.7;
            obj.style.width = iTarget + "px";
        }
        else {
            obj.style.width = obj.offsetWidth + obj.speed + "px";
        }
    }
};

function startMove2(obj, iTarget) {
    if (obj.timer) {
        clearInterval(obj.timer);
    }
    obj.timer = setInterval(function() {
        doMove2(obj, iTarget);
    }, 30)
};

function doMove2(obj, iTarget) {
    obj.speed -= 3;
    if (Math.abs(iTarget - obj.offsetWidth) < 1 && Math.abs(obj.speed) < 1) {
        clearInterval(obj.timer);
        obj.timer = null;
    }
    else {
        if (obj.offsetWidth + obj.speed <= iTarget) {
            obj.speed *= -0.7;
            obj.style.width = iTarget + "px";
        }
        else {
            obj.style.width = obj.offsetWidth + obj.speed + "px";
        }
    }
};