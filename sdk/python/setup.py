from setuptools import setup, find_packages

with open('README.md', 'r', encoding='utf-8') as f:
    long_description = f.read()

setup(
    name='laravel-api-client',
    version='1.0.0',
    description='Official Python client for Laravel REST API with Sanctum authentication',
    long_description=long_description,
    long_description_content_type='text/markdown',
    author='Laravel API Team',
    author_email='api@example.com',
    url='https://github.com/yourusername/laravel-api-client-python',
    project_urls={
        'Bug Tracker': 'https://github.com/yourusername/laravel-api-client-python/issues',
        'Documentation': 'https://github.com/yourusername/laravel-api-client-python#readme',
        'Source Code': 'https://github.com/yourusername/laravel-api-client-python',
    },
    packages=find_packages(),
    install_requires=[
        'requests>=2.28.0',
    ],
    classifiers=[
        'Development Status :: 4 - Beta',
        'Intended Audience :: Developers',
        'License :: OSI Approved :: MIT License',
        'Programming Language :: Python :: 3',
        'Programming Language :: Python :: 3.8',
        'Programming Language :: Python :: 3.9',
        'Programming Language :: Python :: 3.10',
        'Programming Language :: Python :: 3.11',
        'Programming Language :: Python :: 3.12',
        'Topic :: Software Development :: Libraries :: Python Modules',
        'Topic :: Internet :: WWW/HTTP',
    ],
    python_requires='>=3.8',
    keywords='laravel api client rest sanctum authentication',
    license='MIT',
)
