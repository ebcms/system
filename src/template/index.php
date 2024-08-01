{include common/header@php94/admin}
<script>
    var EBCMS = {};
    $(function() {
        EBCMS.stoped = 0;
        EBCMS.stop = function(message) {
            EBCMS.stoped = 1;
            $("#stophandle").hide();
            $("#progress_main input").attr("disabled", "disabled");
            setTimeout(function() {
                EBCMS.console(message, 'red');
                EBCMS.console("完毕<hr>");
                EBCMS.checknew();
            }, 1000)
        };
        EBCMS.start = function() {
            if (confirm('升级前请做备份，立即升级吗？')) {
                EBCMS.stoped = 0;
                $("#stophandle").show();
                EBCMS.check();
            }
        }
        EBCMS.check = function() {
            if (EBCMS.stoped) {
                return;
            }
            EBCMS.console("版本检测...");
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/check')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.stop(response.message);
                    } else {
                        EBCMS.console(response.message);
                        EBCMS.source();
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.source = function() {
            if (EBCMS.stoped) {
                return;
            }
            EBCMS.console("获取资源信息...");
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/source')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.stop(response.message);
                    } else {
                        EBCMS.console(response.message);
                        EBCMS.download();
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.download = function() {
            if (EBCMS.stoped) {
                return;
            }
            EBCMS.console("开始下载~");
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/download')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.stop(response.message);
                    } else {
                        EBCMS.console(response.message);
                        EBCMS.backup();
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.backup = function() {
            if (EBCMS.stoped) {
                return;
            }
            EBCMS.console("程序备份中...");
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/backup')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.stop(response.message);
                    } else {
                        EBCMS.console(response.message);
                        EBCMS.cover();
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.cover = function() {
            if (EBCMS.stoped) {
                return;
            }
            EBCMS.console("程序升级中...");
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/cover')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.rollback(response.message);
                    } else {
                        EBCMS.console("程序升级完毕");
                        EBCMS.install();
                    }
                },
                error: function(context) {
                    EBCMS.rollback("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.install = function() {
            if (EBCMS.stoped) {
                return;
            }
            EBCMS.console("数据升级中...");
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/install')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.rollback(response.message);
                    } else {
                        EBCMS.console("数据升级完毕");
                        EBCMS.console("升级成功！");
                        EBCMS.console(response.message + "<hr>尝试继续升级...<hr>");
                        EBCMS.check();
                    }
                },
                error: function(context) {
                    EBCMS.rollback("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.rollback = function(msg) {
            if (EBCMS.stoped) {
                return;
            }
            EBCMS.console(msg, 'red');
            EBCMS.console("回滚中...");
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/rollback')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.console(response.message, 'red');
                        EBCMS.console('回滚失败', 'red');
                        EBCMS.console('<input type="button" value="再次回滚" onclick="EBCMS.rollback(\'再次回滚\');this.setAttribute(\'disabled\', \'disabled\')">');
                    } else {
                        EBCMS.stop(response.message);
                    }
                },
                error: function(context) {
                    EBCMS.console('回滚失败' + context.statusText, 'red');
                    EBCMS.console('<input type="button" value="再次回滚" onclick="EBCMS.rollback(\'再次回滚\');this.setAttribute(\'disabled\', \'disabled\')">');
                }
            });
        };
        EBCMS.checknew = function() {
            $.ajax({
                type: "GET",
                url: "{echo $router->build('/ebcms/system/check')}",
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        EBCMS.console(response.message, "red");
                        EBCMS.console('<input type="button" value="重新检测" onclick="EBCMS.checknew();this.setAttribute(\'disabled\', \'disabled\')">');
                    } else {
                        EBCMS.console(response.message, "red");
                        EBCMS.console('<input type="button" value="立即升级" onclick="EBCMS.start();this.setAttribute(\'disabled\', \'disabled\')">');
                    }
                }
            });
        }
        EBCMS.console = function(message, color) {
            $(".console").append("<div style=\"color:" + (color ? color : 'white') + "\">[" + (new Date()).toLocaleString() + "] " + message + "</div>");
            $(".console").scrollTop(99999999);
        }
    });
</script>
<div class="my-4 h1">系统升级</div>
<div class="my-4">
    <button class="btn btn-secondary" onclick="$('.console').html('');EBCMS.checknew();">清理日志</button>
    <button class="btn btn-warning" onclick="EBCMS.stop('停止')" id="stophandle" style="display: none;">停止</button>
</div>
<div id="progress_main" class="my-4">
    <div class="version"></div>
    <style>
        .console {
            background-color: #000;
            height: 300px;
            width: 100%;
            overflow-y: auto;
        }
    </style>
    <div class="console mt-3 p-2 text-white">
    </div>
</div>
<script>
    $(function() {
        EBCMS.checknew();
    });
</script>
{include common/footer@php94/admin}