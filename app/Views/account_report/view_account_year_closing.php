<?php
/**
 * The MIT License (MIT)
 *
 * Webzash - Easy to use web based double entry accounting software
 *
 * Copyright (c) 2014 Prashant Shah <pshah.mumbai@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
 
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#pbar_lable").hide();
	$("#pbar").hide();
});
function hidepbar()
{
    $("#pbar_lable").hide();
    $("#pbar").val(0);
	$("#pbar").hide();
	step = 0;
}
function reinit()
{
prog=0;
prev_step=0;
step=1;
//avoid loop
pg=0;
prev_tot=0;
errloop = 0;
tot_cnt1 = 0; //for perc calculation
rem_perc = 0;
}

var step=1;
var pbarstep={
    1:{"lable":"Create Acc year data","value":"25"},
    2:{"lable":"Create Ledger data","value":"25"},
    3:{"lable":"Rollback data","value":"100"}
};
var prog=0;
var prev_step=0;
//avoid loop
var pg=0;
var prev_tot=0;
var errloop = 0;
var tot_cnt1 = 0; //for perc calculation
var rem_perc = 0;
var pref_lab = {};
var j=0;

//cache param
var prev_pref="";
var year1=0;
var prev_year1=0;
function getObj(str)
{
    var res = {};
    arr = str.split("&");
    $.each(arr,(i,ele)=>{
        var arr1 = ele.split("=");
        res[arr1[0]] = arr1[1];
    })
    return res;
}
function bt_submit(data1,j)
{
    j = j|0;
    
    pref ="";
    //alert(pref);
    $("#pbar_lable").show();
	$("#pbar").show();
   //var dat= {"data" : {"Setting" : {"prev_year":"2023","year":"2024","step":3} }};
  // return rollbk(dat);
    var data = {};
    if(step==1)
    data = {"data" :{"Setting": getObj($("#Setting").serialize())}};
    else
    data = data1;
    
    if(step == 3)
    {
        
        alert("Successfully migrated ");
        
        hidepbar();
        window.location.reload();
        return;
    }
    
    if(step > 3)
    {
        hidepbar();
        return;
    }
    
    $("#pbar_lable").html(pbarstep[step]["lable"]+" for Migration");
    $.get("<?php echo base_url();?>/accountreport/account_year_closing",data,(res)=>{
        res = $.parseJSON(res);
        if("undefined" != typeof res.rollback && res.rollback=="-1")
        {
        alert(res.err_msg);
        hidepbar();
        return;
        }
        
        //increase progress bar
        if(prev_step != step || step == 2)
        {
        if(step == 2)
        {
             var tot_cnt = res.tot_cnt|0;
             if(pg == 0) //init
             {
             tot_cnt1 = tot_cnt;
             rem_perc = prog;
             }
             
             var cur_qty = pg * 20;
             //75% => calc
             var blcalc = (cur_qty / tot_cnt1) * 75;
             prog = rem_perc + blcalc;
             console.log(prog,rem_perc,blcalc,tot_cnt1,pg,cur_qty);
        }
        else
        prog += pbarstep[step]["value"] | 0;
        
        //console.log(prog,blcalc,cur_tot_prog,bl,blperc);
        $("#pbar").val(prog);
        }
        
        prev_step = step;
        //if success
        if(step < 3 && res.data == "success")
        {
            var prev_year = res.prev_year;
            var year = res.year;
            year1 = year;
            prev_year1 = prev_year;
            if(step == 2)
            {
                var tot_cnt = res.tot_cnt|0;
                //avoid loop
                if(prev_tot == tot_cnt)
                {
                    errloop++;
                    if(errloop > 5)
                    {
                        alert("Something went wrong");
                        var dat= {"data" : {"Setting" : {"prev_year":prev_year,"year":year,"step":4,"tot_cnt":tot_cnt,"pag":pg++,"pref":pref} }};
                        rollbk(dat);
                        return;
                    }
                }
                
                prev_tot = tot_cnt;
                if(tot_cnt ==0 || res.empty_ledger == 1)
                step++;
                
                var dat= {"data" : {"Setting" : {"prev_year":prev_year,"year":year,"step":step,"tot_cnt":tot_cnt,"pag":pg++,"pref":pref} }};
            }
            else
            {
                step++;
                //alert(pref);
                var dat= {"data" : {"Setting" : {"prev_year":prev_year,"year":year,"step":step,"pref":pref} }};
            }
            
            
            setTimeout(function () {
            bt_submit(dat,j);
            },1200)
        }
        else
        {
            var prev_year = res.prev_year;
            var year = res.year;
            
            var dat= {"data" : {"Setting" : {"prev_year":prev_year,"year":year,"step":step,"pref":pref} }};
            rollbk(dat);
        }
        
    }
    ).fail((err)=>{
        alert("Something went wrong");
         var dat= {"data" : {"Setting" : {"prev_year":prev_year1,"year":year1,"step":step,"pref":""} }};
         rollbk(dat);
        return;
        
        
        var res = $.parseJSON(err.responseText);
        if("undefined" != res["data"] && res.data == "success")
        {
            year1 = year1|0;
            prev_year1 = prev_year1|0;
            //console.log(prev_year1,year1,"err");
            if(prev_year1 != 0 && year1!=0)
            {
            var dat= {"data" : {"Setting" : {"prev_year":prev_year1,"year":year1,"step":step,"pref":prev_pref} }};
             setTimeout(function () {
            bt_submit(dat,j);
            },500)
            }
        }
         
        
    })
    
    
}
var cont_err=0;
function rollbk(dat)
{
    
    return;
}
</script>

<style>
    .thead{
        color: #fff;
        background-color: red;
    }
    a:hover { text-decoration: none; }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2> ACCOUNTS</h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-2"><h2>Account Year Closing</h2></div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    <div class="body">
                        <form id = "Setting" name = "Setting" action="#" method="post">
                            <div class="row">
                                <input type = "hidden" id = "step" name = "step" value = "1" />
    	                        <input type = "hidden" id = "pref" name = "pref" value = "" />
    	                        <div class="col-md-1" style="margin-left:10px">Year </div>
                                <div class="col-md-2"><input type = "text" readonly="true" style="margin-bottom:10px;margin-left:6px" id = "fy_end" name = "fy_end" value = "<?php echo $fy_end; ?>" /></div>
                                <div>
                                <?php
                        	    if($show_bt == 1){
                        	        
                        	    ?>
                        	    <button type="button" id="add_bt1" onclick="bt_submit()" class="btn btn-primary addleetdata float-left">Submit</button>
                        	    <?php } 
                        	    ?>
                                </div>
                            </div>
                        </form>
                        <?php
                        	if($show_bt == 1)
                        	{
                        	echo '<label id="pbar_lable" for="pbar">Start Migration:</label>
                                  <progress id="pbar" value="0" max="100"> 0% </progress>';
                        	}
                        ?>
                          
                    </div>
        </div>
    </div>
</section>
