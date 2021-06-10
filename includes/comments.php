<?php
/**
 * comments.php
 * 
 * 评论区
 * 
 * @author      熊猫小A
 * @version     2019-01-15 0.1
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$setting = $GLOBALS['VOIDSetting'];
$parameter = array(
    'parentId'      => $this->hidden ? 0 : $this->cid,
    'parentContent' => $this->row,
    'respondId'     => $this->respondId,
    'commentPage'   => $this->request->filter('int')->commentPage,
    'allowComment'  => $this->allow('comment')
);
$this->widget('VOID_Widget_Comments_Archive', $parameter)->to($comments);
?>

<div class="comments-container float-up">
    <section id="comments" class="container">
        <!--评论框-->
        <?php if($this->allow('comment')): ?>
            <?php $this->header('commentReply=1&description=0&keywords=0&generator=0&template=0&pingback=0&xmlrpc=0&wlw=0&rss2=0&rss1=0&antiSpam=0&atom'); ?>
            <div id="<?php $this->respondId(); ?>" class="respond">
                <div class="cancel-comment-reply" role=button>
                    <?php $comments->cancelReply(); ?>
                </div>
                <h3 id="response" class="widget-title text-left">评论</h3>
                <?php if(!empty($setting['commentNotification'])): ?>
                    <p class="comment-notification notice"><?php echo $setting['commentNotification']; ?></p>
                <?php endif; ?>
                <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form">
                    <?php if($this->user->hasLogin()): ?>
                    <p id="logged-in" 
                        data-name="<?php $this->user->screenName(); ?>" 
                        data-url="<?php $this->user->url(); ?>" 
                        data-email="<?php $this->user->mail(); ?>" ><?php _e('登录为: '); ?>
                        <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
                        . <a no-pjax href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a>
                    </p>
                    <?php else: ?>
                        <div class="comment-info-input">
                        <input aria-label="昵称" type="text" name="author" id="author" required placeholder="昵称" value="<?php $this->remember('author'); ?>" />
                        <input aria-label="Email<?php echo Helper::options()->commentsRequireMail? '(必填)' : '(选填)' ?>" 
                            type="email" name="mail" id="mail" 
                            placeholder="Email<?php echo Helper::options()->commentsRequireMail? '(必填)' : '(选填)' ?>" 
                            <?php echo Helper::options()->commentsRequireMail? 'required' : '' ?>
                            value="<?php $this->remember('mail'); ?>" />
                        </div>
                    <?php endif; ?>
                    <p style="margin-top:0">
                        <textarea aria-label="评论输入框" class="input-area" rows="5" name="text" id="textarea" 
                            placeholder="评论内容" 
                            style="resize:none;"><?php $this->remember('text'); ?></textarea>
                    </p>
                    <p class="comment-buttons">
                        <span class="表情" aria-label="表情按钮" role="button"></span>
                        <?php if(Utils::isPluginAvailable('CommentToMail') || Utils::isPluginAvailable('Mailer')): ?>
                        <span class="comment-mail-me">
                            <input aria-label="新回复通知" name="receiveMail" type="checkbox" value="yes" id="receiveMail" checked />
                            <label for="receiveMail">新回复通知</label>
                        </span>
                        <?php endif; ?>
                        <button id="comment-submit-button" type="submit" class="submit btn btn-normal">评论</button>
                    </p>
                </form>
            </div>
        <?php endif; ?>
        
        <!--历史评论-->
        <h3 class="comment-separator">
            <div class="comment-tab-current">
                <?php if($this->allow('comment')): ?>
                    <span class="comment-num">
                        <?php $this->commentsNum('评论列表', '共 1 条评论', '共 <span class="num">%d</span> 条评论'); ?>
                    </span>
                <?php else :?>
                    <span class="comment-num">评论已关闭</span>
                <?php endif;?>
            </div>
        </h3>
        <?php if ($comments->have()): ?>
            <?php $comments->listComments(array(
            'before'        =>  '<div class="comment-list">',
            'after'         =>  '</div>',
            'avatarSize'    =>  64,
            'dateFormat'    =>  'Y-m-d H:i'
            )); ?>
            <?php $comments->pageNav('<span aria-label="评论上">←</span>', '<span aria-label="评论下">→</span>', 1, '...', 'wrapClass=pager&prevClass=prev&nextClass=next'); ?>
        <?php endif; ?>
    </section>
</div>