<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:76:"F:\phpstudy\WWW\tp-admin\public/../application/admin\view\index\welcome.html";i:1541405861;}*/ ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>canvas时钟</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            background: url(/static/images/bg.jpg);
            background-size: cover;
            width: 100%;
            height: 100%;
        }

        #myCanvas {
            margin: 10px;
        }

        div {
            margin: 4%;
            moz-user-select: -moz-none;
            -moz-user-select: none;
            -o-user-select: none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        span {
            display: inline-block;
            width: 100px;
            height: 50px;
            background: pink;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            line-height: 50px;
            border-radius: 30px;
            margin-left: 30px;
            cursor: pointer;
        }

        #bottom {
            position: absolute;
            bottom: 0px;
            right: 0px;
            font-size: 15px;
            text-align: right;
        }


    </style>

</head>

<body>
<center>

    <canvas id="myCanvas" width="460" height="460"></canvas>

    <div>
        <span onclick="larger()">增大</span>
        <span onclick="smaller()">缩小</span>
    </div>

</center>
<div id="bottom"></div>


</body>
<script type="text/javascript">
    var can = document.getElementById("myCanvas");
    var width = can.width;
    var height = can.height;
    var ctx = can.getContext("2d");
    var img = new Image();
    img.src = "/static/images/owl.jpg";
    var secondMusic = document.getElementById("secondMusic");

    var r = width / 2;
    ctx.translate(r, r);
    var secShadow = 0;
    var rem = width / 400;

    //画背景
    function drawBackGround() {
        ctx.lineWidth = 4 * rem;
        var rr = r - ctx.lineWidth;
        ctx.beginPath();
        ctx.arc(0, 0, rr, 0, 2 * Math.PI);
        ctx.clip();
        ctx.save();
        ctx.globalAlpha = 0.4;
        ctx.drawImage(img, -rr, -rr, width, height);
        ctx.restore();
        ctx.stroke();
        ctx.closePath();
        drawNumber();
        drawDot();
    }

    //写出12个时钟数字
    function drawNumber() {
        var hourNumbers = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        hourNumbers.forEach(function(number, i) {
            ctx.font = 20 * rem + 'px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            var rad = 2 * Math.PI / 12 * (i + 1);
            var x = (r - 30 * rem) * Math.sin(rad);
            var y = -(r - 30 * rem) * Math.cos(rad);

            ctx.fillText(number, x, y);
        })
    }

    //画出60个分钟小点
    function drawDot() {
        for(var i = 0; i < 60; i++) {
            var rad = 2 * Math.PI / 60 * (i + 1);
            var x = (r - 30 * rem) * Math.sin(rad);
            var y = -(r - 30 * rem) * Math.cos(rad);
            ctx.beginPath();
            ctx.save();

            if((i + 1) % 5 != 0) ctx.arc(x, y, 2.5 * rem, 0, 2 * Math.PI);

            if(i + 1 == secShadow) {
                ctx.save();
                ctx.fillStyle = "gold";
                ctx.shadowColor = "black";
                ctx.shadowBlur = 8;
                ctx.fill();
                ctx.restore();
            } else {
                ctx.save();
                ctx.fillStyle = "#B7B7B7";
                ctx.fill()
                ctx.restore();
            };
        }
    }

    //画出时针
    function drawHour(hour, minute) {
        ctx.save();
        ctx.beginPath();
        ctx.lineWidth = 12 * rem;
        ctx.strokeStyle = "#222222";
        ctx.lineCap = "round";

        var rad = 2 * Math.PI / 12 * hour;
        var rad_min = 2 * Math.PI / 12 / 60 * minute;
        ctx.rotate(rad + rad_min);
        ctx.moveTo(0, 10 * rem);
        ctx.lineTo(0, -r * 0.4);
        ctx.stroke();
        ctx.restore();
        ctx.closePath();
    }

    //画出分针
    function drawMinutes(minute, second) {
        ctx.save();
        ctx.beginPath();
        ctx.lineWidth = 10 * rem;
        ctx.strokeStyle = "gold";
        ctx.fillStyle = "gold";

        var rad = 2 * Math.PI / 60 * minute;
        var rad_min = 2 * Math.PI / 60 / 60 * second;
        ctx.rotate(rad + rad_min);

        ctx.moveTo(0, -r * 0.6);
        ctx.lineTo(-5 * rem, 15 * rem);
        ctx.lineTo(5 * rem, 15 * rem);

        ctx.fill();

        ctx.restore();
        ctx.closePath();
    }

    //画出秒针
    function drawSecond(second) {
        ctx.save();
        drawEyes(second);
        ctx.beginPath();

        ctx.fillStyle = "#FF5809";
        ctx.lineCap = "round";

        var rad = 2 * Math.PI / 60 * second;
        ctx.rotate(rad);
        ctx.moveTo(0, -r * 0.8);
        ctx.lineTo(-5 * rem, 15 * rem);
        ctx.lineTo(5 * rem, 15 * rem);

        ctx.fill();
        ctx.restore();
        ctx.closePath();


//        secondMusic.play();

    }
    //画出猫头鹰的眼睛
    function drawEyes(second) {
        ctx.save();
        ctx.beginPath();

        var rad = 2 * Math.PI / 60 * second;
        ctx.globalAlpha = 0.4;
        ctx.fillStyle = "#342427";

        var x1 = -31 * rem;
        var x2 = 32 * rem;
        var r1 = 10 * rem;
        var y1 = -44 * rem;
        var y2 = -44 * rem;
        var r2 = 12 * rem;

        ctx.arc(x1 + 8 * rem * Math.sin(rad), y1 - 10 * rem * Math.cos(rad), r1, 0, 2 * Math.PI);
        ctx.arc(x2 + 10 * rem * Math.sin(rad), y2 - 11 * rem * Math.cos(rad), r2, 0, Math.PI * 2);

        ctx.fill();

        ctx.restore();
        ctx.closePath();
    }

    //根据当前时间画出时钟
    function drawClock() {
        ctx.clearRect(-r, -r, width, height);

        var now = new Date();
        var hour = now.getHours();
        var minute = now.getMinutes();
        if(minute < 10) minute = "0" + minute;
        var second = now.getSeconds();
        if(second < 10) second = "0" + second;
        secShadow = second;

        drawBackGround();

        drawSecond(second);
        drawHour(hour, minute);
        drawMinutes(minute, second);

        var timer = document.getElementById("bottom");
        var timeStr = hour + ":" + minute + ":" + second;

        ctx.beginPath();
        ctx.arc(0, 0, 8 * rem, 0, Math.PI * 2);
        ctx.fill();
        ctx.closePath();

    }

    img.onload = function() {

        drawClock();
    };

    setInterval(drawClock, 1000);

    //放大时钟
    function larger() {
        ctx.save();
        can.width += 20;
        can.height += 20;
        width = can.width;
        height = can.height;
        r = width / 2;
        ctx.translate(r, r);
        rem = width / 400;
        drawClock();
        ctx.restore();
    }

    //缩小时钟
    function smaller() {
        ctx.save();
        if(can.width >= 20) can.width -= 20;
        if(can.height >= 20) can.height -= 20;
        width = can.width;
        height = can.height;
        r = width / 2;
        ctx.translate(r, r);
        rem = width / 400;
        drawClock();
        ctx.restore();
    }
</script>

</html>