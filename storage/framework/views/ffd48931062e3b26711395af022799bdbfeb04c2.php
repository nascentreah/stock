

<?php $__env->startSection('title'); ?>
    Privacy Policy
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="ui one column tablet stackable <?php echo e($inverted); ?> grid container">
        <div class="column">
            <p>
            This privacy notice discloses the privacy practices for <a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(route('frontend.index')); ?></a>. This privacy notice applies solely to information collected by this website. It will notify you of the following:
            </p>
            <div class="ui bulleted list">
                <div class="item">What personally identifiable information is collected from you through the website, how it is used and with whom it may be shared.</div>
                <div class="item">What choices are available to you regarding the use of your data.</div>
                <div class="item">The security procedures in place to protect the misuse of your information.</div>
                <div class="item">How you can correct any inaccuracies in the information.</div>
            </div>
            <h2 class="ui header">Information Collection, Use, and Sharing</h2>
            <p>
            We are the sole owners of the information collected on this site. We only have access to/collect information that you voluntarily give us via email or other direct contact from you. We will not sell or rent this information to anyone.
            </p>
            <p>
            We will use your information to respond to you, regarding the reason you contacted us. We will not share your information with any third party outside of our organization, other than as necessary to fulfill your request, e.g. to ship an order.
            </p>
            <p>
            Unless you ask us not to, we may contact you via email in the future to tell you about specials, new products or services, or changes to this privacy policy.
            </p>
            <h2 class="ui header">Your Access to and Control Over Information</h2>
            <p>
            You may opt out of any future contacts from us at any time. You can do the following at any time by contacting us via the email address or phone number given on our website:
            </p>
            <div class="ui bulleted list">
                <div class="item">See what data we have about you, if any.</div>
                <div class="item">Change/correct any data we have about you.</div>
                <div class="item">Have us delete any data we have about you.</div>
                <div class="item">Express any concern you have about our use of your data.</div>
            </div>
            <h2 class="ui header">Security</h2>
            <p>
            We take precautions to protect your information. When you submit sensitive information via the website, your information is protected both online and offline.
            </p>
            <p>
            Wherever we collect sensitive information (such as credit card data), that information is encrypted and transmitted to us in a secure way. You can verify this by looking for a lock icon in the address bar and looking for "https" at the beginning of the address of the Web page.
            </p>
            <p>
            While we use encryption to protect sensitive information transmitted online, we also protect your information offline. Only employees who need the information to perform a specific job (for example, billing or customer service) are granted access to personally identifiable information. The computers/servers in which we store personally identifiable information are kept in a secure environment.
            </p>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>