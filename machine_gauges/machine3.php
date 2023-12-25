
<div class="col-lg-3">

    <div id="preview-textfield" style="text-align:center;">machine3</div>
    <div style = "text-align:center;">
    <canvas style="align:center;" name="machine3" id="machine3"
            data-type="radial-gauge"
            data-width="400"
            data-height="400"
            data-units="m / min"
            data-title="false"
            data-value="0"
            data-animate-on-init="true"
            data-animated-value="true"
            data-min-value="0"
            data-max-value="500"
            data-major-ticks="0,20,40,60,80,100,120,140,180,260,340,360,380,400,420,440,460,500"
            data-minor-ticks="2"
            data-stroke-ticks="false"
            data-highlights='[
                { "from": 50, "to": 400, "color": "rgba(0,255,0,1)" },
                { "from": 0, "to": 50, "color": "rgba(255,255,0,1)" },
                { "from": 400, "to": 500, "color": "rgba(255,30,0,1)" }
            ]'
            data-color-plate="transparent"
            data-color-major-ticks="#f5f5f5"
            data-color-minor-ticks="#ddd"
            data-color-title="#fff"
            data-color-units="black"
            data-color-numbers="black"
            data-color-needle-start="rgba(240, 128, 128, 1)"
            data-color-needle-end="rgba(255, 160, 122, .9)"
            data-value-box="false"
            data-animation-rule="bounce"
            data-animation-duration="500"
            data-border-outer-width="3"
            data-border-middle-width="3"
            data-border-inner-width="3"
    ></canvas></div>



  <script type="text/javascrip<>">
var gauge = new RadialGauge({
    renderTo: 'machine3'
}).draw();
  </script>

  </div>
