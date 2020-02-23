<?php
/**
 *  插件设置页面
 */
function WPUFile_setting_page() {
// 如果当前用户权限不足
	if (!current_user_can('manage_options')) {
		wp_die('Insufficient privileges!');
	}

	$this_options = get_option('WPUFile_options');
	if ($this_options && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
        $this_options['no_local_file'] = (isset($_POST['no_local_file'])) ? True : False;
        $this_options['bucket'] = (isset($_POST['bucket'])) ? sanitize_text_field(trim(stripslashes($_POST['bucket']))) : '';
		$this_options['endpoint'] = (isset($_POST['endpoint'])) ? sanitize_text_field(trim(stripslashes($_POST['endpoint']))) : '';
        $this_options['UCLOUD_PUBLIC_KEY'] = (isset($_POST['UCLOUD_PUBLIC_KEY'])) ? sanitize_text_field(trim(stripslashes($_POST['UCLOUD_PUBLIC_KEY']))) : '';
        $this_options['UCLOUD_PRIVATE_KEY'] = (isset($_POST['UCLOUD_PRIVATE_KEY'])) ? sanitize_text_field(trim(stripslashes($_POST['UCLOUD_PRIVATE_KEY']))) : '';

        // 不管结果变没变，有提交则直接以提交的数据 更新 WPUFile_options
        update_option('WPUFile_options', $this_options);

        # 替换 upload_url_path 的值
        update_option('upload_url_path', esc_url_raw(trim(trim(stripslashes($_POST['upload_url_path'])))));

        ?>
        <div style="font-size: 25px;color: red; margin-top: 20px;font-weight: bold;"><p>UCloud UFile插件设置保存完毕!!!</p></div>

        <?php

    }

?>

    <style>
        table {
            border-collapse: collapse;
        }

        table, td, th {border: 1px solid #cccccc;padding:5px;}
        .buttoncss {background-color: #4CAF50;
            border: none;cursor:pointer;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;border-radius: 5px;
            font-size: 12px;font-weight: bold;
        }
        .buttoncss:hover {
            background-color: #008CBA;
            color: white;
        }
        input{border: 1px solid #ccc;padding: 5px 0px;border-radius: 3px;padding-left:5px;}
    </style>
<div style="margin:5px;">
    <h2>WordPress UCloud UFile存储设置</h2>
    <hr/>
    
        <p>WordPress UCloud对象存储（WPUFile），基于UCloud UFile与WordPress实现静态资源到对象存储中。提高网站项目的访问速度，以及静态资源的安全存储功能。目前UCLOUD对象存储提供每月20GB流量，适合入门用户使用。</p>
        <p>插件网站： <a href="https://www.laobuluo.com" target="_blank">老部落</a> / <a href="https://www.laobuluo.com/2924.html" target="_blank">WPUFile插件发布页面地址</a>  / 站长创业交流QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（宗旨：多做事，少说话）  / 微信公众号：  <font color="blue"> <b>imweber</b></font></p>
        <p>推荐文章： <a href="https://www.laobuluo.com/2113.html" target="_blank">新人建站常用的虚拟主机/云服务器 常用主机商选择建议</a></p>

      <hr/>
    <form action="<?php echo wp_nonce_url('./admin.php?page=' . WPUFile_BASEFOLDER . '/actions.php'); ?>" name="wpcosform" method="post">
        <table>
            <tr>
                <td style="text-align:right;">
                    <b>存储空间名称：</b>
                </td>
                <td>
                    <input type="text" name="bucket" value="<?php echo esc_attr($this_options['bucket']); ?>" size="50"
                           placeholder="比如：laobuluo"/>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
                    <b>所属地域（注意格式）：</b>
                </td>
                <td>
                    <input type="text" name="endpoint" value="<?php echo esc_attr($this_options['endpoint']); ?>" size="50"
                           placeholder="注意说明格式"/>
                           <p><b>注意事项：</b>如果我们创建空间域名是<code>laobuluo.cn-bj.ufileos.com</code>，则所属地域应该填写<code>.cn-bj.ufileos.com</code></p>
                </td>
            </tr>

            <tr>
               <td style="text-align:right;">
                    <b>存储空间域名：</b>
              </td>
                <td>
                    <input type="text" name="upload_url_path" value="<?php echo esc_url(get_option('upload_url_path')); ?>" size="50"
                           placeholder="自带免费域名或者是自定义域名"/>

                    <p><b>注意事项：</b>注意前面需要加上http或者https，我们可以用免费自带的或者自定义域名。免费赠送的类似<code>  
laobuluo.cn-bj.ufileos.com</code>，或者我们自定义域名</p>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
                    <b>令牌公钥：</b>
                 </td>
                <td><input type="text" name="UCLOUD_PUBLIC_KEY" value="<?php echo esc_attr($this_options['UCLOUD_PUBLIC_KEY']); ?>" size="50" placeholder="UCLOUD_PUBLIC_KEY"/></td>
            </tr>
            <tr>
               <td style="text-align:right;">
                    <b>令牌私钥：</b>
                 </td>
                <td>
                    <input type="text" name="UCLOUD_PRIVATE_KEY" value="<?php echo esc_attr($this_options['UCLOUD_PRIVATE_KEY']); ?>" size="50" placeholder="UCLOUD_PRIVATE_KEY"/>
                    <p><b>注意事项：</b>我们需要到当前对象存储管理菜单中的【令牌管理】创建令牌名称且需要授权创建的对象存储归属管理。</p>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <b>不在本地保存：</b>
                </td>
                <td>
                    <input type="checkbox"
                           name="no_local_file"
                        <?php
                            if ($this_options['no_local_file']) {
                                echo 'checked="TRUE"';
                            }
					    ?>
                    />

                    <p>如果不想同步在服务器中备份静态文件就 "勾选"。</p>
                </td>
            </tr>
            
            <tr>
                <th>
                    
                </th>
                <td><input type="submit" name="submit" value="保存UCloud UFile设置" class="buttoncss" /></td>

            </tr>
        </table>
    </form>
</div>
<?php
}
?>