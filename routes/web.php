<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaglineController;
use App\Http\Controllers\AboutSchoolController;
use App\Http\Controllers\VisionMissionController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\SchoolHistoryController;
use App\Http\Controllers\WhyChooseController;
use App\Http\Controllers\CampusAreaController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\TeacherAndStaffController;
use App\Http\Controllers\WhatsupCategoryController;
use App\Http\Controllers\WhatsupController;
use App\Http\Controllers\GalleryCategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CISUpdateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\YoutubeShortLinkController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ImportantDateController;
use App\Http\Controllers\ImportantDateBranchController;
use App\Http\Controllers\CISIconController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\CisInNumberController;
use App\Http\Controllers\CareerController;

//Landing
use App\Http\Controllers\Landing\Index;
use App\Http\Controllers\Landing\About;
use App\Http\Controllers\Landing\CisInNumber as LandingCisInNumber;
use App\Http\Controllers\Landing\CISIcon as LandingCISIcon;
use App\Http\Controllers\Landing\CampusDetail as LandingCampusDetail;
use App\Http\Controllers\Landing\Curricula as LandingCurricula;
use App\Http\Controllers\Landing\Events as LandingEvents;
use App\Http\Controllers\Landing\Achievements as LandingAchievements;
use App\Http\Controllers\Landing\Career as LandingCareer;
use App\Http\Controllers\Landing\Gallery as LandingGallery;
use App\Http\Controllers\Landing\WhatsUp as LandingWhatsUp;
use App\Http\Controllers\Landing\ImportantDates as LandingImportantDates;
use App\Http\Controllers\Landing\SchoolHistory as LandingSchoolHistory;
use App\Http\Controllers\Landing\SchoolLeadership as LandingSchoolLeadership;
use App\Http\Controllers\Landing\Testimonial as LandingTestimonial;
use App\Http\Controllers\Landing\WhyChoose as LandingWhyChoose;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'id'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back();
})->name('locale.switch');

Route::get('/', [Index::class, 'index']);
Route::get('/about-us', [About::class, 'index']);
Route::get('/curricula', [LandingCurricula::class, 'index'])->name('landing.curricula');
Route::get('/school-history', [LandingSchoolHistory::class, 'index'])->name('landing.school-history');
Route::get('/school-leadership', [LandingSchoolLeadership::class, 'index'])->name('landing.school-leadership');
Route::get('/why-choose', [LandingWhyChoose::class, 'index']);
Route::get('/cis-in-numbers', [LandingCisInNumber::class, 'index']);
Route::get('/important-dates', [LandingImportantDates::class, 'index'])->name('landing.important-dates');
Route::get('/important-dates/events', [LandingImportantDates::class, 'events'])->name('landing.important-dates.events');
Route::get('/events', [LandingEvents::class, 'index'])->name('landing.events');
Route::get('/events/{slug}', [LandingEvents::class, 'show'])->name('landing.events.show');
Route::get('/achievement', [LandingAchievements::class, 'index'])->name('landing.achievement');
Route::get('/cis-icon', [LandingCISIcon::class, 'index'])->name('landing.cis-icon');
Route::get('/cis-icon/{id}/download', [LandingCISIcon::class, 'download'])->name('landing.cis-icon.download');
Route::get('/gallery', [LandingGallery::class, 'index'])->name('landing.gallery');
Route::get('/gallery/{slug}', [LandingGallery::class, 'show'])->name('landing.gallery.show');
Route::get('/career', [LandingCareer::class, 'index'])->name('landing.career');
Route::post('/career/apply', [LandingCareer::class, 'apply'])->name('landing.career.apply');
Route::get('/happenings', [LandingWhatsUp::class, 'index'])->defaults('section', 'happenings')->name('landing.whatsup.happenings');
Route::get('/highlights', [LandingWhatsUp::class, 'index'])->defaults('section', 'highlights')->name('landing.whatsup.highlights');
Route::get('/announcements', [LandingWhatsUp::class, 'index'])->defaults('section', 'announcements')->name('landing.whatsup.announcements');
Route::get('/{section}/{slug}', [LandingWhatsUp::class, 'show'])
    ->whereIn('section', ['happenings', 'highlights', 'announcements'])
    ->name('landing.whatsup.show');
Route::get('/{section}', [LandingWhatsUp::class, 'index'])
    ->whereIn('section', ['happenings', 'highlights', 'announcements'])
    ->name('landing.whatsup.index');
Route::get('/testimonials', [LandingTestimonial::class, 'index'])->name('landing.testimonials');
Route::get('/school/{slug}', [LandingCampusDetail::class, 'show'])->name('landing.school.show');



Route::get('/dashboard', function () {
    return view('admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/visitor-stats', [DashboardController::class, 'visitorStats'])->name('dashboard.visitor-stats');

    //Menu Management
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/data', [MenuController::class, 'data'])->name('menu.data');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    //Role Management
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/data', [RoleController::class, 'data'])->name('role.data');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

    //User Management
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('/user/{id}/change-password', [UserController::class, 'changePassword'])->name('user.change-password');

    Route::post('/admin/important-dates/events', [ImportantDateController::class, 'store'])->name('important-dates.store');
    Route::get('/admin/important-dates', [ImportantDateController::class, 'primary'])->name('important-dates.primary');
    Route::get('/admin/important-dates/events', [ImportantDateController::class, 'events'])->name('important-dates.events');
    //Important Date Branches
    Route::get('/important-date-branches', [ImportantDateBranchController::class, 'index'])->name('important-date-branches.index');
    Route::get('/important-date-branches/data', [ImportantDateBranchController::class, 'data'])->name('important-date-branches.data');
    Route::post('/important-date-branches', [ImportantDateBranchController::class, 'store'])->name('important-date-branches.store');
    Route::get('/important-date-branches/{id}', [ImportantDateBranchController::class, 'edit'])->name('important-date-branches.edit');
    Route::put('/important-date-branches/{id}', [ImportantDateBranchController::class, 'update'])->name('important-date-branches.update');
    Route::delete('/important-date-branches/{id}', [ImportantDateBranchController::class, 'destroy'])->name('important-date-branches.destroy');

    //System Settings
    Route::get('/system-management', [SystemSettingsController::class, 'index'])->name('settings.index');
    Route::put('/system-management', [SystemSettingsController::class, 'update'])->name('settings.update');

    //Tagline Management
    Route::get('/tagline', [TaglineController::class, 'index'])->name('tagline.index');
    Route::put('/tagline', [TaglineController::class, 'update'])->name('tagline.update');

    //About School Management
    Route::get('/about-school', [AboutSchoolController::class, 'index'])->name('about-school.index');
    Route::get('/about-school/data', [AboutSchoolController::class, 'data'])->name('about-school.data');
    Route::post('/about-school', [AboutSchoolController::class, 'store'])->name('about-school.store');
    Route::get('/about-school/{id}', [AboutSchoolController::class, 'edit'])->name('about-school.edit');
    Route::put('/about-school/{id}', [AboutSchoolController::class, 'update'])->name('about-school.update');
    Route::delete('/about-school/{id}', [AboutSchoolController::class, 'destroy'])->name('about-school.destroy');

    //Vission & Mission Management
    Route::get('/vision-mission', [VisionMissionController::class, 'index'])->name('vision-mission.index');
    Route::get('/vision-mission/data', [VisionMissionController::class, 'data'])->name('vision-mission.data');
    Route::post('/vision-mission', [VisionMissionController::class, 'store'])->name('vision-mission.store');
    Route::get('/vision-mission/{id}', [VisionMissionController::class, 'edit'])->name('vision-mission.edit');
    Route::put('/vision-mission/{id}', [VisionMissionController::class, 'update'])->name('vision-mission.update');
    Route::delete('/vision-mission/{id}', [VisionMissionController::class, 'destroy'])->name('vision-mission.destroy');

    //Curriculum Management
    Route::get('/curriculum', [CurriculumController::class, 'index'])->name('curriculum.index');
    Route::get('/curriculum/data', [CurriculumController::class, 'data'])->name('curriculum.data');
    Route::post('/curriculum', [CurriculumController::class, 'store'])->name('curriculum.store');
    Route::get('/curriculum/{id}', [CurriculumController::class, 'edit'])->name('curriculum.edit');
    Route::put('/curriculum/{id}', [CurriculumController::class, 'update'])->name('curriculum.update');
    Route::delete('/curriculum/{id}', [CurriculumController::class, 'destroy'])->name('curriculum.destroy');

    //School History Management
    Route::get('/admin/school-history', [SchoolHistoryController::class, 'index'])->name('school-history.index');
    Route::put('/admin/school-history', [SchoolHistoryController::class, 'update'])->name('school-history.update');

    //Why Choose Us Management
    Route::get('/why-choose-cis', [WhyChooseController::class, 'index'])->name('why-choose.index');
    Route::put('/why-choose-cis', [WhyChooseController::class, 'update'])->name('why-choose.update');

    //Campus Area Management
    Route::get('/campus-area', [CampusAreaController::class, 'index'])->name('campus-area.index');
    Route::get('/campus-area/data', [CampusAreaController::class, 'data'])->name('campus-area.data');
    Route::post('/campus-area', [CampusAreaController::class, 'store'])->name('campus-area.store');
    Route::get('/campus-area/{id}', [CampusAreaController::class, 'edit'])->name('campus-area.edit');
    Route::put('/campus-area/{id}', [CampusAreaController::class, 'update'])->name('campus-area.update');
    Route::delete('/campus-area/{id}', [CampusAreaController::class, 'destroy'])->name('campus-area.destroy');

    //Facility Management
    Route::get('/school-facility', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/school-facility/data', [FacilityController::class, 'data'])->name('facilities.data');
    Route::post('/school-facility', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('/school-facility/{id}', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/school-facility/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/school-facility/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

    //Campus Management
    Route::get('/our-campus', [CampusController::class, 'index'])->name('campus.index');
    Route::get('/our-campus/data', [CampusController::class, 'data'])->name('campus.data');
    Route::post('/our-campus', [CampusController::class, 'store'])->name('campus.store');
    Route::get('/our-campus/{id}', [CampusController::class, 'edit'])->name('campus.edit');
    Route::put('/our-campus/{id}', [CampusController::class, 'update'])->name('campus.update');
    Route::delete('/our-campus/{id}', [CampusController::class, 'destroy'])->name('campus.destroy');

    //Teacher and Staff Management
    Route::get('/our-teacher-and-staff', [TeacherAndStaffController::class, 'index'])->name('teacher-and-staff.index');
    Route::get('/our-teacher-and-staff/data', [TeacherAndStaffController::class, 'data'])->name('teacher-and-staff.data');
    Route::post('/our-teacher-and-staff', [TeacherAndStaffController::class, 'store'])->name('teacher-and-staff.store');
    Route::get('/our-teacher-and-staff/{id}', [TeacherAndStaffController::class, 'edit'])->name('teacher-and-staff.edit');
    Route::put('/our-teacher-and-staff/{id}', [TeacherAndStaffController::class, 'update'])->name('teacher-and-staff.update');
    Route::delete('/our-teacher-and-staff/{id}', [TeacherAndStaffController::class, 'destroy'])->name('teacher-and-staff.destroy');

    //Whatsup Category Management
    Route::get('/whatsup-category', [WhatsupCategoryController::class, 'index'])->name('whatsup-category.index');
    Route::get('/whatsup-category/data', [WhatsupCategoryController::class, 'data'])->name('whatsup-category.data');
    Route::post('/whatsup-category', [WhatsupCategoryController::class, 'store'])->name('whatsup-category.store');
    Route::get('/whatsup-category/{id}', [WhatsupCategoryController::class, 'edit'])->name('whatsup-category.edit');
    Route::put('/whatsup-category/{id}', [WhatsupCategoryController::class, 'update'])->name('whatsup-category.update');
    Route::delete('/whatsup-category/{id}', [WhatsupCategoryController::class, 'destroy'])->name('whatsup-category.destroy');

    //Whatsup Management
    Route::get('/whatsup', [WhatsupController::class, 'index'])->name('whatsup.index');
    Route::get('/whatsup/data', [WhatsupController::class, 'data'])->name('whatsup.data');
    Route::post('/whatsup', [WhatsupController::class, 'store'])->name('whatsup.store');
    Route::get('/whatsup/{id}', [WhatsupController::class, 'edit'])->name('whatsup.edit');
    Route::put('/whatsup/{id}', [WhatsupController::class, 'update'])->name('whatsup.update');
    Route::delete('/whatsup/{id}', [WhatsupController::class, 'destroy'])->name('whatsup.destroy');

    //Gallery Category Management
    Route::get('/gallery-category', [GalleryCategoryController::class, 'index'])->name('gallery-category.index');
    Route::get('/gallery-category/data', [GalleryCategoryController::class, 'data'])->name('gallery-category.data');
    Route::post('/gallery-category', [GalleryCategoryController::class, 'store'])->name('gallery-category.store');
    Route::get('/gallery-category/{id}', [GalleryCategoryController::class, 'edit'])->name('gallery-category.edit');
    Route::put('/gallery-category/{id}', [GalleryCategoryController::class, 'update'])->name('gallery-category.update');
    Route::delete('/gallery-category/{id}', [GalleryCategoryController::class, 'destroy'])->name('gallery-category.destroy');

    //Gallery Management
    Route::get('/admin/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('/admin/gallery/data', [GalleryController::class, 'data'])->name('gallery.data');
    Route::post('/admin/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('/admin/gallery/{id}', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/admin/gallery/{id}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/admin/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    //Events Management
    Route::get('/admin/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/admin/events/data', [EventController::class, 'data'])->name('events.data');
    Route::post('/admin/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/admin/events/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/admin/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/admin/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    //Youtube Short Link Management
    Route::get('/youtube-short-link', [YoutubeShortLinkController::class, 'index'])->name('youtube-short-link.index');
    Route::get('/youtube-short-link/data', [YoutubeShortLinkController::class, 'data'])->name('youtube-short-link.data');
    Route::post('/youtube-short-link', [YoutubeShortLinkController::class, 'store'])->name('youtube-short-link.store');
    Route::get('/youtube-short-link/{id}', [YoutubeShortLinkController::class, 'edit'])->name('youtube-short-link.edit');
    Route::put('/youtube-short-link/{id}', [YoutubeShortLinkController::class, 'update'])->name('youtube-short-link.update');
    Route::delete('/youtube-short-link/{id}', [YoutubeShortLinkController::class, 'destroy'])->name('youtube-short-link.destroy');

    //Achievement Management
    Route::get('/admin/achievement', [AchievementController::class, 'index'])->name('achievement.index');
    Route::get('/admin/achievement/data', [AchievementController::class, 'data'])->name('achievement.data');
    Route::post('/admin/achievement', [AchievementController::class, 'store'])->name('achievement.store');
    Route::get('/admin/achievement/{id}', [AchievementController::class, 'edit'])->name('achievement.edit');
    Route::put('/admin/achievement/{id}', [AchievementController::class, 'update'])->name('achievement.update');
    Route::delete('/admin/achievement/{id}', [AchievementController::class, 'destroy'])->name('achievement.destroy');

    //CIS Update Management
    Route::get('/cis-update', [CISUpdateController::class, 'index'])->name('cis-update.index');
    Route::get('/cis-update/data', [CISUpdateController::class, 'data'])->name('cis-update.data');
    Route::post('/cis-update', [CISUpdateController::class, 'store'])->name('cis-update.store');
    Route::get('/cis-update/{id}', [CISUpdateController::class, 'edit'])->name('cis-update.edit');
    Route::put('/cis-update/{id}', [CISUpdateController::class, 'update'])->name('cis-update.update');
    Route::delete('/cis-update/{id}', [CISUpdateController::class, 'destroy'])->name('cis-update.destroy');
    Route::post('/cis-update/{id}/status', [CISUpdateController::class, 'updateStatus'])->name('cis-update.status');

    Route::get('/admin/cis-icon', [CISIconController::class, 'index'])->name('cis-icon.index');
    Route::get('/admin/cis-icon/data', [CISIconController::class, 'data'])->name('cis-icon.data');
    Route::post('/admin/cis-icon', [CISIconController::class, 'store'])->name('cis-icon.store');
    Route::get('/admin/cis-icon/{id}', [CISIconController::class, 'edit'])->name('cis-icon.edit');
    Route::put('/admin/cis-icon/{id}', [CISIconController::class, 'update'])->name('cis-icon.update');
    Route::delete('/admin/cis-icon/{id}', [CISIconController::class, 'destroy'])->name('cis-icon.destroy');

    Route::get('/testimonial', [TestimonialController::class, 'index'])->name('testimonial.index');
    Route::get('/testimonial/data', [TestimonialController::class, 'data'])->name('testimonial.data');
    Route::post('/testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');
    Route::get('/testimonial/{id}', [TestimonialController::class, 'edit'])->name('testimonial.edit');
    Route::put('/testimonial/{id}', [TestimonialController::class, 'update'])->name('testimonial.update');
    Route::delete('/testimonial/{id}', [TestimonialController::class, 'destroy'])->name('testimonial.destroy');

    Route::get('/social-media', [SocialMediaController::class, 'index'])->name('social-media.index');
    Route::get('/social-media/data', [SocialMediaController::class, 'data'])->name('social-media.data');
    Route::post('/social-media', [SocialMediaController::class, 'store'])->name('social-media.store');
    Route::get('/social-media/{id}', [SocialMediaController::class, 'edit'])->name('social-media.edit');
    Route::put('/social-media/{id}', [SocialMediaController::class, 'update'])->name('social-media.update');
    Route::delete('/social-media/{id}', [SocialMediaController::class, 'destroy'])->name('social-media.destroy');

    Route::get('/cis-in-number', [CisInNumberController::class, 'index'])->name('cis-in-number.index');
    Route::get('/cis-in-number/data', [CisInNumberController::class, 'data'])->name('cis-in-number.data');
    Route::post('/cis-in-number', [CisInNumberController::class, 'store'])->name('cis-in-number.store');
    Route::get('/cis-in-number/{id}', [CisInNumberController::class, 'edit'])->name('cis-in-number.edit');
    Route::put('/cis-in-number/{id}', [CisInNumberController::class, 'update'])->name('cis-in-number.update');
    Route::delete('/cis-in-number/{id}', [CisInNumberController::class, 'destroy'])->name('cis-in-number.destroy');

    Route::get('/admin/career', [CareerController::class, 'index'])->name('career.index');
    Route::get('/admin/career/data', [CareerController::class, 'data'])->name('career.data');
    Route::post('/admin/career', [CareerController::class, 'store'])->name('career.store');
    Route::get('/admin/career/{id}', [CareerController::class, 'edit'])->name('career.edit');
    Route::put('/admin/career/{id}', [CareerController::class, 'update'])->name('career.update');
    Route::delete('/admin/career/{id}', [CareerController::class, 'destroy'])->name('career.destroy');

});

require __DIR__.'/auth.php';
